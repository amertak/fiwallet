// GLOBAL objects
$ = jQuery = require('jquery');
Nette = require('./NetteForms/NetteForms');
require('bootstrap');
require('./../node_modules/react-bootstrap-daterangepicker/lib/daterangepicker.js');
HighCharts = require('highcharts-browserify');

$(function () {

  //Show react app if dashboard element is present
  var dashboard = document.getElementById('dashboard');
  if (dashboard) {
    var React = require('react');
    var Router = require('react-router');
    var DefaultRoute = Router.DefaultRoute;
    var Route = Router.Route;

    var App = require('./components/App.react');
    var TransactionDetail = require('./components/TransactionDetail.react');
    var Dashboard = require('./components/Dashboard.react');

    var routes = (
      <Route name="app" path="/" handler={App}>
        <Route name="transaction" path="/transaction/:transactionId" handler={TransactionDetail}/>
        <DefaultRoute handler={Dashboard}/>
      </Route>
    );

    Router.run(routes, function (Handler) {
      React.render(<Handler/>, dashboard);
    });
  }

  $('input[type=date]').attr('type', 'text').daterangepicker({singleDatePicker: true, format: 'YYYY-MM-DD', locale: {firstDay: 1}});

  $(".notRegistered").click(function(e){
    e.preventDefault();
    $("#frm-loginForm").slideToggle('slow');
    $("#frm-registrationForm").slideToggle('slow');
  });

  $(".backToLogin").click(function(e){
    e.preventDefault();
    if($("#frm-registrationForm").is(":visible")) {
      $("#frm-registrationForm").slideToggle('slow');
    }
    if($("#formForgotPassword").is(":visible")) {
      $("#formForgotPassword").slideToggle('slow');
    }
    $("#frm-loginForm").slideToggle('slow');
  });

  $(".forgotPassword").click(function(e){
    e.preventDefault();
    $("#frm-loginForm").slideToggle('slow');
    $("#formForgotPassword").slideToggle('slow');
  });

});
