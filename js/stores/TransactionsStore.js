/*
 * Copyright (c) 2014, Facebook, Inc.
 * All rights reserved.
 *
 * This source code is licensed under the BSD-style license found in the
 * LICENSE file in the root directory of this source tree. An additional grant
 * of patent rights can be found in the PATENTS file in the same directory.
 *
 * TodoStore
 */

var AppDispatcher = require('../dispatcher/AppDispatcher');
var EventEmitter = require('events').EventEmitter;
var DashboardConstants = require('../constants/DashboardConstants');
var _ = require('lodash');
var assign = require('object-assign');
var jQuery = require('jquery');
var moment = require('moment');

var CHANGE_EVENT = 'change';

var _allTransactions = null;
var _transactions = null;
var _newTransaction = null;

var _accounts = null;
var _account = null;

var _filters = null;
var _activeFilters = [];

function loadFilters(callback) {
  jQuery.get('/api/filters', function (data) {
    _filters = data.data;

    callback();
  });
};

function loadTransactions(callback) {
  jQuery.get('/api/transactions?limit=20', function (data) {
    _transactions = data.data;

    for (var i = 0; i < _transactions.length; i++) {
      _transactions[i].tags = _.map(_.pluck(_transactions[i].tags, 'name'), function (item) {
        return item.toLowerCase();
      });
      _transactions[i].date = moment(_transactions[i].date).format('YYYY-MM-DD');
      _transactions[i].amount = parseInt(_transactions[i].amount, 10);
    }

    _allTransactions = _transactions;

    callback();
  });
};

function loadTransaction(id, callback) {
  jQuery.get('/api/transactions/' + id, function (data) {
    var transaction = data.data;

    transaction.tags = _.map(_.pluck(transactions.tags, 'name'), function (item) {
      return item.toLowerCase();
    });
    transaction.date = moment(transaction.date).format('YYYY-MM-DD');
    transaction.amount = parseInt(transaction.amount, 10);

    _transactions.push(transaction);

    callback();
  });
};

function loadAccounts(callback) {
  jQuery.get('/api/accounts', function (data) {
    _accounts = data.data;
    _account = _accounts[0];

    for (var i = 0; i < _accounts.length; i++) {
      _accounts[i].balance = parseInt(_accounts[i].balance, 10);
    }

    callback();
  });
}

function saveTransaction(callback) {
  jQuery.post('/api/transactions', _newTransaction).done(function (data) {
    _newTransaction = null;

    var transaction = data.data;
    _.find(_accounts, function (acc) {
      return acc.id === transaction.accountId
    }).balance = parseInt(transaction.account.balance, 10);
    transaction.date = moment(transaction.date).format('YYYY-MM-DD');
    transaction.amount = parseInt(transaction.amount, 10);
    _transactions.push(transaction);
    callback();
  });
}

function deleteTransaction(id, callback) {
  jQuery.ajax({
    url: '/api/transactions/' + id,
    type: 'DELETE',
    success: function (data) {
      var transaction = data.data;
      _.find(_accounts, function (acc) {
        return acc.id === transaction.accountId
      }).balance = parseInt(transaction.account.balance, 10);
      callback();
    }
  });
}

function updateTransaction(id, callback) {
  jQuery.ajax({
      url: '/api/transactions/' + id,
      type: 'PUT',
      data: getTransaction(id),
      success: function (data) {
        var transaction = data.data;
        transaction.amount = parseInt(transaction.amount, 10);
        transaction.tags = _.map(_.pluck(transaction.tags, 'name'), function (item) {
          return item.toLowerCase();
        });
        _.find(_accounts, function (acc) {
          return acc.id === transaction.accountId
        }).balance = parseInt(transaction.account.balance, 10);
        callback();
      }
    }
  );
}

function confirmTransaction(id, callback) {
  jQuery.ajax({
      url: '/api/transactions/' + id + '/confirm/',
      type: 'PUT',
      success: function (data) {
        var transaction = data.data;
        _.find(_accounts, function (acc) {
          return acc.id === transaction.accountId
        }).balance = transaction.account.balance;

        _.find(_transactions, function (tran) {
          return tran.id === id
        }).confirmed = true;

        callback();
      }
    }
  );
}

function getTransaction(id) {
  return _.find(_transactions, function (tran) {
    return tran.id == id
  });
}

function filterTransactions() {
  var activeFilters = _.filter(_filters, function (filter) {
    return _.includes(_activeFilters, filter.id)
  });
  _transactions = _allTransactions;

  if (activeFilters.length === 0) {
    return;
  }

  for (var i = 0; i < activeFilters.length; i++) {
    var filter = activeFilters[i];

    for (var s = 0; s < filter.conditions.length; s++) {
      var condition = filter.conditions[s];

      if (condition.property === 'amount') {
        _transactions = _.filter(_transactions, function (transaction) {
          var c = condition;
          return eval('transaction.amount ' + c.operator + ' ' + c.value)
        });
      }

      if (condition.property === 'tags') {
        _transactions = _.filter(_transactions, function (transaction) {
          var c = condition;
          return _.includes(transaction.tags, c.value.toLowerCase());
        });
      }
    }
  }
}

var _count = 0;
function loadingDone(count) {
  _count++;

  if (_count === count) {
    TransactionsStore.emitChange();
    _count = 0;
  }
}

var TransactionsStore = assign({}, EventEmitter.prototype, {

  /**
   * @return {object}
   */
  getState: function () {
    return {
      transactions: _transactions,
      accounts: _accounts,
      account: _account,
      newTransaction: _newTransaction,
      filters: _filters,
      activeFilters: _activeFilters
    };
  },

  emitChange: function () {
    this.emit(CHANGE_EVENT);
  },

  /**
   * @param {function} callback
   */
  addChangeListener: function (callback) {
    this.on(CHANGE_EVENT, callback);
  },

  /**
   * @param {function} callback
   */
  removeChangeListener: function (callback) {
    this.removeListener(CHANGE_EVENT, callback);
  }
});


AppDispatcher.register(function (action) {
  switch (action.actionType) {

    case DashboardConstants.LOAD:
      loadAccounts(function () {
        loadingDone(3);
      });
      loadTransactions(function () {
        loadingDone(3);
      });
      loadFilters(function () {
        loadingDone(3);
      });
      break;

    case DashboardConstants.SAVE:
      saveTransaction(function () {
        TransactionsStore.emitChange();
      });
      break;

    case DashboardConstants.CREATE:
      _newTransaction = {
        date: moment().format('YYYY-MM-DD'),
        amount: '',
        name: 'Description',
        accountId: _account.id
      };
      TransactionsStore.emitChange();
      break;

    case DashboardConstants.NEWTRANSACTION_DATE:
      if (_newTransaction) {
        _newTransaction.date = action.date;
      }
      TransactionsStore.emitChange();
      break;

    case DashboardConstants.NEWTRANSACTION_AMOUNT:
      if (_newTransaction) {
        _newTransaction.amount = action.amount;
      }
      TransactionsStore.emitChange();
      break;

    case DashboardConstants.NEWTRANSACTION_NAME:
      if (_newTransaction) {
        _newTransaction.name = action.name;
      }
      TransactionsStore.emitChange();
      break;

    case DashboardConstants.NEWTRANSACTION_CANCEL:
      _newTransaction = null;
      TransactionsStore.emitChange();
      break;

    case DashboardConstants.SELECT_ACCOUNT:
      _account = _.find(_accounts, function (acc) {
        return acc.id == action.id
      });
      TransactionsStore.emitChange();
      break;

    case DashboardConstants.TRANSACTION_AMOUNT:
      getTransaction(action.id).amount = action.value;
      TransactionsStore.emitChange();
      break;

    case DashboardConstants.TRANSACTION_NAME:
      getTransaction(action.id).name = action.value;
      TransactionsStore.emitChange();
      break;

    case DashboardConstants.TRANSACTION_DATE:
      getTransaction(action.id).date = action.value;
      TransactionsStore.emitChange();
      break;

    case DashboardConstants.TRANSACTION_TAGS:
      getTransaction(action.id).tags = action.value;
      TransactionsStore.emitChange();
      break;

    case DashboardConstants.TRANSACTION_NOTES:
      getTransaction(action.id).notes = action.value;
      TransactionsStore.emitChange();
      break;

    case DashboardConstants.TRANSACTION_DELETE:
      _transactions = _.reject(_transactions, function (tran) {
        return tran.id == action.id
      });
      deleteTransaction(action.id, function () {
        TransactionsStore.emitChange();
      });
      TransactionsStore.emitChange();
      break;

    case DashboardConstants.TRANSACTION_UPDATE:
      updateTransaction(action.id, function () {
        TransactionsStore.emitChange();
      });
      TransactionsStore.emitChange();
      break;

    case DashboardConstants.TRANSACTION_CONFIRM:
      confirmTransaction(action.id, function () {
        TransactionsStore.emitChange();
      });
      break;

    case DashboardConstants.TRANSACTION_LOAD:
      loadTransaction(action.id, function () {
        TransactionsStore.emitChange();
      });
      break;

    case DashboardConstants.FILTER_ENABLE:
      _activeFilters = _.without(_activeFilters, action.id);
      _activeFilters.push(action.id);
      filterTransactions();
      TransactionsStore.emitChange();
      break;

    case DashboardConstants.FILTER_DISABLE:
      _activeFilters = _.without(_activeFilters, action.id);
      filterTransactions();
      TransactionsStore.emitChange();
      break;

    default:
    // no op
  }
});

module.exports = TransactionsStore;
