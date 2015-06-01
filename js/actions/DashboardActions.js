var AppDispatcher = require('../dispatcher/AppDispatcher');
var DashboardConstants = require('../constants/DashboardConstants');

var DashboardActions = {
  loadData: function () {
    AppDispatcher.dispatch({
      actionType: DashboardConstants.LOAD
    });
  },
  saveTransaction: function (transaction) {
    AppDispatcher.dispatch({
      actionType: DashboardConstants.SAVE,
      transaction: transaction
    });
  },
  createNewTransaction: function () {
    AppDispatcher.dispatch({
      actionType: DashboardConstants.CREATE
    });
  },
  newTransactionSetAmount: function (amount) {
    AppDispatcher.dispatch({
      actionType: DashboardConstants.NEWTRANSACTION_AMOUNT,
      amount: amount
    });
  },
  newTransactionSetName: function (name) {
    AppDispatcher.dispatch({
      actionType: DashboardConstants.NEWTRANSACTION_NAME,
      name: name
    });
  },
  newTransactionSetDate: function (date) {
    AppDispatcher.dispatch({
      actionType: DashboardConstants.NEWTRANSACTION_DATE,
      date: date
    });
  },
  cancelNewTransaction: function () {
    AppDispatcher.dispatch({
      actionType: DashboardConstants.NEWTRANSACTION_CANCEL
    });
  },
  selectAccount: function (id) {
    AppDispatcher.dispatch({
      id: id,
      actionType: DashboardConstants.SELECT_ACCOUNT
    })
  },
  transactionName: function (id, value) {
    AppDispatcher.dispatch({
      id: id,
      actionType: DashboardConstants.TRANSACTION_NAME,
      value: value
    })
  },
  transactionAmount: function (id, value) {
    AppDispatcher.dispatch({
      id: id,
      actionType: DashboardConstants.TRANSACTION_AMOUNT,
      value: value
    })
  },
  transactionDate: function (id, value) {
    AppDispatcher.dispatch({
      id: id,
      actionType: DashboardConstants.TRANSACTION_DATE,
      value: value
    })
  },
  transactionTags: function (id, value) {
    AppDispatcher.dispatch({
      id: id,
      actionType: DashboardConstants.TRANSACTION_TAGS,
      value: value
    })
  },
  transactionNotes: function (id, value) {
    AppDispatcher.dispatch({
      id: id,
      actionType: DashboardConstants.TRANSACTION_NOTES,
      value: value
    })
  },
  transactionUpdate: function (id) {
    AppDispatcher.dispatch({
      id: id,
      actionType: DashboardConstants.TRANSACTION_UPDATE
    })
  },
  transactionDelete: function (id) {
    AppDispatcher.dispatch({
      id: id,
      actionType: DashboardConstants.TRANSACTION_DELETE
    })
  },
  confirmTransaction: function(id) {
    AppDispatcher.dispatch({
      id: id,
      actionType: DashboardConstants.TRANSACTION_CONFIRM
    });
  },
  loadTransaction: function (id) {
    AppDispatcher.dispatch({
      id: id,
      actionType: DashboardConstants.TRANSACTION_LOAD
  });
  },
  enableFilter: function (id) {
    AppDispatcher.dispatch({
      id: id,
      actionType: DashboardConstants.FILTER_ENABLE
    })
  },
  disableFilter: function (id) {
    AppDispatcher.dispatch({
      id: id,
      actionType: DashboardConstants.FILTER_DISABLE
    })
  }
};

module.exports = DashboardActions;
