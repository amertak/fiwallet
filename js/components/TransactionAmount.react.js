var React = require('react');
var EditableTextMixin = require('./EditableTextMixin.react');
var classset = require('react-classset');

var TransactionAmount = React.createClass({
  mixins: [EditableTextMixin],
  propTypes: {
    transaction: React.PropTypes.object.isRequired,
    currency: React.PropTypes.string.isRequired,
    onChange: React.PropTypes.func.isRequired,
    editable: React.PropTypes.bool.isRequired,
    onLostFocus: React.PropTypes.func
  },
  componentDidMount: function () {
    this.setState({editable: this.props.editable});
  },
  onLostFocus: function () {
    if (this.props.onLostFocus) {
      this.props.onLostFocus();
    }
  },
  render: function () {
    var classes = classset({
      'editableText': true
    });

    if (!this.state.editable) {
      return (<div className="huge">
        <span onClick={this.handleClick} className={classes}>{this.props.transaction.amount}&nbsp;{this.props.currency}</span>
      </div>);
    }
    else {
      var height = { height: '42px', textAlign: 'right', fontSize: '22px'};

      return (<div className="huge">
        <div className="col-xs-12">
          <input type="number" className="form-control" placeholder="Amount " style={height} ref="input" onKeyDown={this.handleKeyDown} onChange={this.props.onChange} onBlur={this.handleLostFocus} value={this.props.transaction.amount} />
        </div>
      </div>);
    }
  }
});

module.exports = TransactionAmount;
