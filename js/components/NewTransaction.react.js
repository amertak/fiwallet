var React = require('react');
var classset = require('react-classset');
var DateRangePicker = require('react-bootstrap-daterangepicker');
var moment = require('moment');
var DashboardActions = require('../actions/DashboardActions');
var TransactionAmount = require('./TransactionAmount.react');
var TransactionName = require('./TransactionName.react');

var NewTransaction = React.createClass({
  propTypes: {
    transaction: React.PropTypes.object.isRequired,
    account: React.PropTypes.object.isRequired
  },
  handleCalendarApply: function (event, picker) {
    DashboardActions.newTransactionSetDate(picker.startDate.format('YYYY-MM-DD'));
  },
  handleSaveClick: function (event) {
    DashboardActions.saveTransaction();
    event.target.setAttribute('disabled', 'disabled');
  },
  handleClose: function () {
    DashboardActions.cancelNewTransaction();
  },
  handleAmountChange: function (event) {
    DashboardActions.newTransactionSetAmount(event.target.value);
  },
  onNameChanged: function (event) {
    DashboardActions.newTransactionSetName(event.target.value);
  },
  render: function () {
    var style = {cursor: 'pointer'};

    var locale = {
      firstDay: 1
    };

    var classes = classset({
      'panel': true,
      'panel-yellow': true
    });

    if (isNaN(parseInt(this.props.transaction.amount, 10))) {
      var disabled = "disabled";
    }

    return (<div className="col-md-3 col-xs-12 col-lg-2 col-sm-6">
      <div className={classes}>
        <div className="panel-heading">
          <div className="row">
            <div className="col-xs-8 text-left">
              <DateRangePicker onApply={this.handleCalendarApply} singleDatePicker={true} timePicker={false} locale={locale}>
                <span style={style}>{this.props.transaction.date}</span>
              </DateRangePicker>
            </div>
            <div className="col-xs-4 text-right icon-text clickable" onClick={this.handleClose}>
              <span className="glyphicon glyphicon-remove-circle" aria-hidden="true"></span>
            </div>
          </div>
          <div className="row">
            <div className="col-xs-12 text-right">
              <TransactionAmount transaction={this.props.transaction} onChange={this.handleAmountChange} editable={true} currency={this.props.account.currency}/>
              <TransactionName transaction={this.props.transaction} onChange={this.onNameChanged} textAlign={'right'} height={'20px'} fontSize={'12px'}/>
            </div>
          </div>
        </div>
        <div className="panel-footer">
          <div className="row">
            <div className="col-xs-12 text-center">
              <button type="button" className="btn btn-success" disabled={disabled} onClick={this.handleSaveClick}>Save</button>
            </div>
          </div>
        </div>
      </div>
    </div>)
  }
});

module.exports = NewTransaction;
