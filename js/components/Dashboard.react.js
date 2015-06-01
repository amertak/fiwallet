var React = require('react');
var TransactionList = require('./TransactionList.react');
var Accounts = require('./Accounts.react');
var Filters = require('./Filters.react');

var Router = require('react-router');
var Link = Router.Link;

var ComponentStateMixin = require('./ComponentStateMixin.react');

var Dashboard = React.createClass({
  mixins: [ComponentStateMixin],
  render: function () {

    if (!this.state.accounts) {
      return null;
    }

    var style = {maxWidth: '400px'};

    if (this.state.accounts.length > 1) {
      var comboBox = <div className="col-lg-12">
        <div className="panel">
          <div className="panel-heading">
            <div className="row">
              <p>
                <Accounts accounts={this.state.accounts} />
              </p>
            </div>
          </div>
        </div>
      </div>;
    }
    else {
      var comboBox = null;
    }

    return (<div>
      <div className="col-lg-12">
        <h1 className="page-header">
          Dashboard
          <small>Overview</small>
        </h1>
        <ol className="breadcrumb">
          <li>
            <i className="fa fa-dashboard"></i>&nbsp;Dashboard</li>
        </ol>
      </div>
      <div className="col-xs-12">
        <Filters filters={this.state.filters} activeFilters={this.state.activeFilters} />
      </div>
    {comboBox}
      <div style={style} className="col-xs-12">
        <div className="panel panel-primary">
          <div className="panel-heading">
            <div className="row">
              <div className="col-xs-4">
                <div className="huge">Balance:</div>
              </div>
              <div className="col-xs-8 text-right">
                <div className="huge">{this.state.account.balance} {this.state.account.currency}</div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div className="row"></div>
      <div className="col-xs-12">
        <TransactionList transactions={this.state.transactions} account={this.state.account} newTransaction={this.state.newTransaction} />
      </div>
    </div>)
  }
});

module.exports = Dashboard;
