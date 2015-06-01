var React = require('react');
var classset = require('react-classset');
var TransactionTags = require('./TransactionTags.react');
var moment = require('moment');
var Router = require('react-router');
var Link = Router.Link;
var _ = require('lodash');
var ComponentStateMixin = require('./ComponentStateMixin.react');
var DateRangePicker = require('react-bootstrap-daterangepicker');
var TagsInput = require('react-tagsinput');
var TransactionAmount = require('./TransactionAmount.react');
var TransactionName = require('./TransactionName.react');
var DashboardActions = require('../actions/DashboardActions');

var TransactionDetail = React.createClass({
  contextTypes: {
    router: React.PropTypes.func
  },
  getInitialState: function () {
    var transactionId = this.context.router.getCurrentParams().transactionId;
    return {id: transactionId};
  },
  mixins: [ComponentStateMixin],
  onAmountChanged: function (event) {
    DashboardActions.transactionAmount(this.state.id, event.target.value);
  },
  onTagsChanged: function (tags) {
    DashboardActions.transactionTags(this.state.id, _.map(tags, function (item) {
      return item.toLowerCase();
    }));
    DashboardActions.transactionUpdate(this.state.id);
  },
  onNotesChanged: function (event) {
    DashboardActions.transactionNotes(this.state.id, event.target.value);
  },
  onDateChanged: function (date, picker) {
    DashboardActions.transactionDate(this.state.id, picker.startDate.format('YYYY-MM-DD'));
    DashboardActions.transactionUpdate(this.state.id);
  },
  onNameChanged: function (event) {
    DashboardActions.transactionName(this.state.id, event.target.value);
  },
  handleDelete: function () {
    if (confirm('Do you really want to delete this transaction?')) {
      DashboardActions.transactionDelete(this.state.id);
      this.context.router.transitionTo('/');
    }
  },
  redirect: function () {
    window.location = '/recurrent-transactions/create-from-transaction/' + this.state.id;
  },
  update: function () {
    DashboardActions.transactionUpdate(this.state.id);
  },
  render: function () {
    var transactionId = this.context.router.getCurrentParams().transactionId;
    var transaction = _.find(this.state.transactions, function (tran) {
      return tran.id == transactionId
    });

    var locale = {
      firstDay: 1
    };

    if (!transaction) {
      DashboardActions.loadTransaction(transactionId);
      return null;
    }

    var style = {cursor: 'pointer'};

    return (<div>
      <div className="col-xs-12">
        <h1 className="page-header">
          <TransactionName transaction={transaction} onChange={this.onNameChanged} onLostFocus={this.update} textAlign={'left'} height={'39px'} fontSize={'27px'}/>
        </h1>
        <ol className="breadcrumb">
          <li>
            <i className="fa fa-dashboard"></i>
          &nbsp;
            <Link to={'/'}>Dashboard</Link>
          </li>
          <li className="active">
            <i className="fa fa-pencil"></i>
          &nbsp;Transaction
          </li>
        </ol>
      </div>

      <div className="row">
        <div className="col-md-6 col-xs-12 col-lg-4 col-sm-8">
          <div className="form-group">
            <label className="control-label" htmlFor="amount">Amount ({transaction.account.currency})</label>
            <input type="number" className="form-control" id="amount" value={transaction.amount} onChange={this.onAmountChanged} onBlur={this.update} />
          </div>
        </div>
      </div>
      <div className="row">
        <div className="col-md-6 col-xs-12 col-lg-4 col-sm-8">
          <div className="form-group">
            <label className="control-label" htmlFor="date">Date</label>
            <DateRangePicker onApply={this.onDateChanged} singleDatePicker={true} timePicker={false} locale={locale}>
              <input type="text" className="form-control" id="date" readOnly="readOnly" style={style} value={transaction.date} />
            </DateRangePicker>
          </div>
        </div>
      </div>
      <div className="row">
        <div className="col-md-6 col-xs-12 col-lg-4 col-sm-8">
          <div className="form-group">
            <label className="control-label" htmlFor="tags">Tags</label>
            <TagsInput id="tags" ref="tags" tags={transaction.tags} onChange={this.onTagsChanged} />
          </div>
        </div>
      </div>
      <div className="row">
        <div className="col-md-6 col-xs-12 col-lg-4 col-sm-8">
          <div className="form-group">
            <label className="control-label" htmlFor="notes">Notes</label>
            <textarea id="notes" className="form-control" placeholder="Insert some notes" value={transaction.notes} onChange={this.onNotesChanged} onBlur={this.update}></textarea>
          </div>
        </div>
      </div>
      <div className="row">
        <div className="col-md-6 col-xs-12 col-lg-4 col-sm-8">
          <button type="button" className="btn btn-warning" onClick={this.redirect}>Convert to recurrent transaction</button>
        </div>
      </div>
      <br />
      <br />
      <div className="row">
        <div className="col-md-4 col-xs-12 col-lg-2 col-sm-8">
          <button type="button" className="btn btn-danger" onClick={this.handleDelete}>Delete transaction</button>
        </div>
      </div>
    </div>
    )
  }
});

module.exports = TransactionDetail;
