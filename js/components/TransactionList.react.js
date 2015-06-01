var React = require('react');
var Transaction = require('./Transaction.react');
var NewTransaction = require('./NewTransaction.react');
var DashboardActions = require('../actions/DashboardActions');
var _ = require('lodash');

var TransactionList = React.createClass({
  propTypes: {
    transactions: React.PropTypes.array,
    newTransaction: React.PropTypes.object,
    account: React.PropTypes.object.isRequired
  },
  handleNewTransactionClick: function () {
    DashboardActions.createNewTransaction();
  },
  render: function () {
    var that = this;
    if (!this.props.transactions) {
      return null;
    }

    var transactions = _.sortBy(this.props.transactions, function (transaction) {
      return new Date(transaction.date);
    }).reverse();

    var components = _.filter(transactions, function (tran) { return tran.accountId === that.props.account.id}).map(function (transaction) {
      return (<Transaction transaction={transaction} key={transaction.id} />)
    });

    if (this.props.newTransaction) {
      components.unshift(<NewTransaction transaction={this.props.newTransaction} key={-999} account={this.props.account} />);
    }

    if (components.length === 0) {
      components = ( <div className="col-lg-12"><div>No transactions found, why dont you create one! :) </div></div>);
    }

    return (<div>
      <div className="row">
        <div className="col-lg-12">
          <button type="button" className="btn btn-warning content-box" onClick={this.handleNewTransactionClick}><span className="glyphicon glyphicon-plus" aria-hidden="true"></span>&nbsp;&nbsp;Add new transaction</button>
        </div>
      </div>
      <div className="row">{components}</div>
    </div>);
  }
});

module.exports = TransactionList;
