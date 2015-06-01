var React = require('react');
var classset = require('react-classset');
var TransactionTags = require('./TransactionTags.react');
var moment = require('moment');
var Router = require('react-router');
var Link = Router.Link;
var DashboardActions = require('../actions/DashboardActions');

var Transaction = React.createClass({
  propTypes: {
    transaction: React.PropTypes.object.isRequired
  },
  handleConfirmClick: function (event) {
    DashboardActions.confirmTransaction(this.props.transaction.id);
    event.target.setAttribute('disabled', 'disabled');
  },
  render: function () {

    var classes = classset({
      'panel': true,
      'panel-red': this.props.transaction.amount <= 0 && this.props.transaction.confirmed,
      'panel-green': this.props.transaction.amount > 0 && this.props.transaction.confirmed,
      'panel-gray': !this.props.transaction.confirmed
    });

    moment.locale('en', {
      calendar: {
        lastDay: '[Yesterday]',
        sameDay: '[Today]',
        nextDay: '[Tomorrow]',
        lastWeek: '[last] dddd',
        nextWeek: 'dddd',
        sameElse: 'L'
      }
    });

    var date = moment(this.props.transaction.date).startOf('day').calendar();
    var url = "/transaction/" + this.props.transaction.id;

    var footer;
    if (this.props.transaction.confirmed) {
      footer = <TransactionTags tags={this.props.transaction.tags} />
    } else {
      footer = <div className="panel-footer">
        <div className="row">
          <div className="col-xs-12 text-center">
            <button type="button" className="btn btn-warning" onClick={this.handleConfirmClick}>Confirm transaction</button>
          </div>
        </div>
      </div>;
    }


    return (<div className="col-md-3 col-xs-12 col-lg-2 col-sm-6">
      <div className={classes}>
        <Link to={url}>
          <div className="panel-heading">
            <div className="row">
              <div className="col-xs-12 text-left">
                  {date}
              </div>
            </div>
            <div className="row">
              <div className="col-xs-12 text-right">
                <div className="huge">{this.props.transaction.amount} {this.props.transaction.account.currency}</div>
                <div>{this.props.transaction.name}</div>
              </div>
            </div>
          </div>
        </Link>
          {footer}
      </div>
    </div>)
  }
});

module.exports = Transaction;
