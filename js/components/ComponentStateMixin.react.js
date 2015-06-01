var React = require('react');
var TransactionsStore = require('../stores/TransactionsStore');

var ComponentStateMixin = {
  getInitialState: function () {
    return TransactionsStore.getState();
  },
  componentDidMount: function () {
    TransactionsStore.addChangeListener(this._onChange);
  },
  componentWillUnmount: function () {
    TransactionsStore.removeChangeListener(this._onChange);
  },
  _onChange: function () {
    this.setState(TransactionsStore.getState());
  }
};

module.exports = ComponentStateMixin;
