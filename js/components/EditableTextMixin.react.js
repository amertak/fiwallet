var React = require('react');

var EditableTextMixin = {
  getInitialState: function () {
    return {
      editable: false,
      changed: false
    };
  },
  componentDidUpdate: function(){
    if (this.state.editable) {
      this.refs.input.getDOMNode().focus();

      if (this.state.changed) {
        this.refs.input.getDOMNode().select();
        this.setState({changed: false});
      }
    }
  },
  handleLostFocus: function () {
    this.setState({editable: false});

    if (this.onLostFocus) {
      this.onLostFocus();
    }
  },
  handleClick: function (){
    this.setState({editable: true, changed: true});
  },
  handleKeyDown: function (event) {
    if (event.keyCode === 13) {
      this.handleLostFocus();
    }
  }
};

module.exports = EditableTextMixin;
