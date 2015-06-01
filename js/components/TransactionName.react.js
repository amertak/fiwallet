var React = require('react');
var EditableTextMixin = require('./EditableTextMixin.react');
var classset = require('react-classset');

var TransactionName = React.createClass({
  mixins: [EditableTextMixin],
  propTypes: {
    transaction: React.PropTypes.object.isRequired,
    onChange: React.PropTypes.func.isRequired,
    onLostFocus: React.PropTypes.func,
    textAlign: React.PropTypes.string.isRequired,
    height: React.PropTypes.string.isRequired,
    fontSize: React.PropTypes.string.isRequired
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
      return (<div onClick={this.handleClick} className={classes}>{this.props.transaction.name}</div>);
    }
    else {
      var height = { height: this.props.height, textAlign: this.props.textAlign, fontSize: this.props.fontSize};

      return (<div>
        <input type="text" className="form-control" style={height} ref="input" onKeyDown={this.handleKeyDown} onChange={this.props.onChange} onBlur={this.handleLostFocus} value={this.props.transaction.name} />
      </div>);
    }
  }
});

module.exports = TransactionName;
