<?php

namespace FiWallet\App\FrontModule\StatisticsModule;

use FiWallet\App\FrontModule\BasePresenter;
use FiWallet\Transactions\Transaction;
use FiWallet\Transactions\TransactionsManager;
use FiWallet\Transactions\TagsManager;
use Kdyby\Doctrine\EntityRepository;
use FiWallet\Users\UserManager;
use Ghunti\HighchartsPHP\Highchart;
use Ghunti\HighchartsPHP\HighchartJsExpr;
use Nette\Application\UI\Form;
use Nette\Utils\DateTime;

/**
 */
class ListPresenter extends BasePresenter
{
    /**
     * @var EntityRepository
     */
    //private $filtersRepository;

    /**
     * @inject
     * @var TransactionsManager
     */
    public $transactionsManager;

    /**
     * @var EntityRepository
     */
    private $transactionsRepository;

    /**
     * @inject
     * @var TagsManager
     */
    public $tagsManager;

    /**
     * @inject
     * @var UserManager
     */
    public $userManager;

    protected function startup()
    {
        parent::startup();
        $this->transactionsRepository = $this->entityManager->getRepository(Transaction::class);
    }

    public function actionDefault($from = null , $to = null, $fromAmount = null , $toAmount = null, $weekFromNow = null)
    {

        $user = $this->user->identity;
        $transactions = $this->transactionsRepository->findBy(['user' => $this->user->identity]);
        $this->template->transactions = $transactions;


        if($from || $to || $fromAmount || $toAmount) {
            $this->template->isFiltered = true;
            $this->template->transactions = $this->transactionsManager->getTransactionFilter($this->user->identity, $from, $to,  $fromAmount, $toAmount );
        }



        $data = $this->tagsManager->findAll();
        $arr = array();
        foreach($data as $tag) {


            $sum = 0;
            foreach( $tag->transactions as $transaction ) {

                if ($transaction->amount < 0) {
                    $sum += $transaction->amount;
                }
            }
            $arr[] = array($tag->name, abs($sum));

        }

        $chart = new Highchart();
        $chart->chart->renderTo = "container";
        $chart->chart->fill = "#FFF";
        $chart->chart->plotBackgroundColor = null;
        $chart->chart->plotBorderWidth = null;
        $chart->chart->plotShadow = false;
        $chart->title->text = "Amount spent by tags";
        $chart->tooltip->formatter = new HighchartJsExpr(
            "function() {
   return '<b>'+ this.point.name +'</b>: '+ this.percentage.toFixed(2) +' %';}");
        $chart->plotOptions->pie->allowPointSelect = 1;
        $chart->plotOptions->pie->cursor = "pointer";
        $chart->plotOptions->pie->showInLegend->enabled = 1;
        $chart->plotOptions->pie->dataLabels->enabled = 1;
        $chart->plotOptions->pie->dataLabels->color = "#000000 ";
        $chart->plotOptions->pie->dataLabels->connectorColor = "#000000 ";
        $chart->plotOptions->pie->dataLabels->formatter = new HighchartJsExpr(
            "function() {
   return '<b>'+ this.point.name +'</b>: '+ this.percentage.toFixed(2) +' %'; }");


        $chart->series[] = array(
            'type' => "pie",
            'name' => "Tags per month",
            'data' => $arr
        );
        $this->template->js = $chart->render("chart1");


        /********************** Day *************************/



        $day = date('w');
        $day = $day-($weekFromNow * 7);
        $week_start = date('Y-m-d', strtotime('-'.($day-1).' days'));
        $week_end = date('Y-m-d',strtotime($week_start."+ 7 days"));

        $data1 = $this->transactionsManager->getTransactionFromTo($user, $week_start, $week_end);

        $days = array("$week_start", date('Y-m-d',strtotime($week_start."+ 1 days")), date('Y-m-d',strtotime($week_start."+ 2 days")),
            date('Y-m-d',strtotime($week_start."+ 3 days")), date('Y-m-d',strtotime($week_start."+ 4 days")),date('Y-m-d',
                strtotime($week_start."+ 5 days")), date('Y-m-d',strtotime($week_start."+ 6 days")));

        $chart = new Highchart();
//        $chart->xAxis->categories = array("Monday", "Tuesday", "Wednesday", "Thursday", "Friday","Saturday", "Sunday" );
        $chart->xAxis->categories = $days;
        $chart->xAxis->labels->rotation = -45;
        $chart->chart->renderTo = "container1";
        $chart->chart->plotBackgroundColor = null;
        $chart->chart->plotBorderWidth = null;
        $chart->chart->plotShadow = false;
        $chart->title->text = "This week amount spent";
        $chart->plotOptions->pie->cursor = "pointer";
        $chart->plotOptions->pie->showInLegend->enabled = 1;
        $chart->plotOptions->pie->dataLabels->enabled = 1;
        $chart->plotOptions->pie->dataLabels->color = "#000000 ";
        $chart->plotOptions->pie->dataLabels->connectorColor = "#000000 ";
        $chart->plotOptions->pie->dataLabels->formatter = new HighchartJsExpr(
            "function() {
   return '<b>'+ this.point.name +'</b>: '+ this.percentage.toFixed(2) +' %'; }");


        //here get data amount per week - some week
        // array("monday", 32823727)
        $arr1 = array();
        foreach($days as $day) {

            $sum_per_day = 0;
            foreach ($data1 as $transaction) {


                if ($day == $transaction->dateOfTransaction->format("Y-m-d")) {
                    if ($transaction->amount < 0) {
                        $sum_per_day += $transaction->amount;
                    }

                }

            }
            $arr1[] = array($day, abs($sum_per_day));

        }
        $chart->series[] = array(
            'type' => "column",
            'name' => "Amount per day",
            'data' => $arr1

        );
        $this->template->js1 = $chart->render("chart2");

//next graph
        $year_start = date("2015-01-01");
        $year_end = date("2015-12-31");

        $data3 = $this->transactionsManager->getTransactionFromTo($user, $year_start, $year_end);
        $years_name = array("January", "February", "March", "April", "May", "Jun", "July", "August", "September", "October", "November", "December");


        $years = array(
            "$year_start" => date('Y-m-d',strtotime($year_start."+ 1 months")),
            date('Y-m-d',strtotime($year_start."+ 1 months")) => date('Y-m-d',strtotime($year_start."+ 2 months")),
            date('Y-m-d',strtotime($year_start."+ 2 months")) => date('Y-m-d',strtotime($year_start."+ 3 months")),
            date('Y-m-d',strtotime($year_start."+ 3 months")) => date('Y-m-d',strtotime($year_start."+ 4 months")),
            date('Y-m-d',strtotime($year_start."+ 4 months")) => date('Y-m-d',strtotime($year_start."+ 5 months")),
            date('Y-m-d',strtotime($year_start."+ 5 months")) => date('Y-m-d',strtotime($year_start."+ 6 months")),
            date('Y-m-d',strtotime($year_start."+ 6 months")) => date('Y-m-d',strtotime($year_start."+ 7 months")),
            date('Y-m-d',strtotime($year_start."+ 7 months")) => date('Y-m-d',strtotime($year_start."+ 8 months")),
            date('Y-m-d',strtotime($year_start."+ 8 months")) => date('Y-m-d',strtotime($year_start."+ 9 months")),
            date('Y-m-d',strtotime($year_start."+ 9 months")) => date('Y-m-d',strtotime($year_start."+ 10 months")),
            date('Y-m-d',strtotime($year_start."+ 10 months")) => date('Y-m-d',strtotime($year_start."+ 11 months")),
            date('Y-m-d',strtotime($year_start."+ 11 months")) => date('Y-m-d',strtotime($year_start."+ 12 months"))

        );
        $arr2 = array();

        $chart = new Highchart();
        $chart->xAxis->categories = $years_name;
        $chart->chart->renderTo = "container3";
        $chart->chart->plotBackgroundColor = null;
        $chart->chart->plotBorderWidth = null;
        $chart->chart->plotShadow = false;
        $chart->title->text = "Difference between income and outcome";
        $chart->plotOptions->pie->cursor = "pointer";
        $chart->plotOptions->pie->showInLegend->enabled = 1;
        $chart->plotOptions->pie->dataLabels->enabled = 1;
        $chart->plotOptions->pie->dataLabels->color = "#000000 ";
        $chart->plotOptions->pie->dataLabels->connectorColor = "#000000 ";
        $chart->plotOptions->pie->dataLabels->formatter = new HighchartJsExpr(
            "function() {
   return '<b>'+ this.point.name +'</b>: '+ this.percentage.toFixed(2) +' %'; }");


        //here get data amount per week - some week
        // array("monday", 32823727)

        foreach($years as $key => $month) {

            $sum_per_month = 0;
            foreach ($data3 as $transaction) {


                if ($key < $transaction->dateOfTransaction->format("Y-m-d") AND $transaction->dateOfTransaction->format("Y-m-d") < $month) {

                    $sum_per_month += $transaction->amount;
                }

            }
            $arr2[] = array($key, $sum_per_month);

        }


        $chart->series[] = array(
            'type' => "column",
            'name' => "Amount per month in this year",
            'data' => $arr2
        );
        $this->template->js3 = $chart->render("chart883");
//next graph

        $chart = new Highchart();
        $chart->xAxis->categories = $years_name;

        $chart->title->text = "Year amounts <b>spent</b> by tags";
        $chart->xAxis->crosshair = true;
        $chart->chart->renderTo = "container2";
        $chart->chart->type = "column";
        $chart->yAxis->min = 0;
        $chart->legend->layout = "vertical";
        $chart->legend->backgroundColor = "#FFFFFF";
        $chart->legend->align = "left";
        $chart->legend->verticalAlign = "top";
        $chart->legend->x = 100;
        $chart->legend->y = 70;
        $chart->legend->floating = 1;
        $chart->legend->shadow = 1;
        $chart->tooltip->formatter = new HighchartJsExpr("function() {
    return '' + this.series.name +' '+ this.y +' CZK';}");

        $chart->plotOptions->column->pointPadding = 0.2;
        $chart->plotOptions->column->borderWidth = 0;

        foreach($data as $tag) {
            $sum_per_month_by_tag = array();
            foreach ($years as $key => $month) {
                $sum = 0;
                foreach( $tag->transactions as $transaction ) {

                    if ($key < $transaction->dateOfTransaction->format("Y-m-d") AND $transaction->dateOfTransaction->format("Y-m-d") < $month) {
                        if ($transaction->amount < 0) {

                            $sum += $transaction->amount;
                        }
                    }
                }
                if ($sum == 0) {
                    $sum_per_month_by_tag[] = null;
                } else {
                    $sum_per_month_by_tag[] = abs($sum);
                }
            }
            $chart->series[] = array("name"=>$tag->name, "data"=>$sum_per_month_by_tag);
        }
        $this->template->js2 = $chart->render("chart4");



        $form = $this->getComponent("fromToForm");
        $form->onSubmit[] = $this->filterFormSubmitted;
        $form->onSubmit[] = function (Form $form) {
            $this->flashSuccess("Data successfully filtered!");
            $this->redirect('default', ['from'=>$form->getValues()->from, 'to'=>$form->getValues()->to,'fromAmount'=>$form->getValues()->fromAmount, 'toAmount'=>$form->getValues()->toAmount]);
        };

        if($from) {
            $this->template->filterFrom = $from;
        }
        if($to) {
            $this->template->filterTo = $to;
        }
        if($fromAmount) {
            $this->template->filterFromAmount = $fromAmount;
        }
        if($toAmount) {
            $this->template->filterToAmount = $toAmount;
        }

        if($weekFromNow) {
            $this->template->weekFromNow = $weekFromNow;
        } else {
            $this->template->weekFromNow = 0;
        }

    }

    public function handleReset(){
        $this->redirect('default');
    }

    public function handleNextWeek($weekFromNow){
        $this->redirect('default', [null, null, null, null, "weekFromNow" => $weekFromNow + 1]);
    }

    public function handlePreviousWeek($weekFromNow){
        $this->redirect('default', [null, null, null, null, "weekFromNow" => $weekFromNow - 1]);
    }

    public function createComponentFromToForm() {
        $form = new Form();
        $form->addText("from", "From date");
        $form->addText("to", "To date");
        $form->addText("fromAmount", "From amount");
        $form->addText("toAmount", "To amount");
        $form->addSubmit("send", "Filter");

        return $form;
    }

    public function filterFormSubmitted(Form $form) {
        $values = $form->values;

        if($values->from || $values->to || $values->fromAmount || $values->toAmount) {
            //values OK
        } else {
            $form->addError("Filter data are not correct.");
        }
    }

}
