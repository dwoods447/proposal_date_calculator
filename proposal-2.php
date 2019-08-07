
    

        <style>
         @import url("https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.9.0/css/all.min.css");
        </style>

        <style>
          @import url('https://cdn.jsdelivr.net/npm/bootstrap@4/dist/css/bootstrap.min.css');
        </style>

        <style>
        @import url("https://cdn.jsdelivr.net/npm/pc-bootstrap4-datetimepicker@4.17/build/css/bootstrap-datetimepicker.min.css");
        </style>

        <style>
         @import url("//unpkg.com/bootstrap-vue@latest/dist/bootstrap-vue.min.css");
        </style>

<div id="programCalculator">
        <div class="container-fluid">
        <h2>{{header}}</h2>
            <div class="row">
                    <div class="col-lg-12">
                        <form @submit.prevent="submitDate()">
                        <div class="row">
                            <div class="col-lg-8">
                                <div class="input-group date">
                                     <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon2"><i class="fas fa-2x fa-calendar-alt"></i></span>
                                    </div>
                                    <date-picker v-validate="'required|date_format:MM/dd/yyyy'" name="dateFormatted" placeholder="MM/DD/YYYY" v-model="dateFormatted" :disabled="inputDisabled" :config="options" style="height: 49px;"></date-picker>
                                </div>
                            </div>   
                            <div class="col-lg-2" v-if="!userSubmitted">
                                <b-button variant="danger" size="lg" @click="submitDate" style="width: 100%;">Calculate</b-button>
                            </div>
                            <div class="col-lg-2">
                                <b-button variant="danger" size="lg" @click="clearDates" style="width: 100%;">Clear</b-button>
                            </div>
                            <div class="col-lg-12"><p style="color: red; margin-left: 20px;">{{message}}</p></div>
                            <ul>
                                <li v-for="error in errors.collect('dateFormatted')" style="color: red; margin-left: 20px;">{{ error }}</li>
                            </ul>
                         </div>
                     </form>
                    </div>
                    <div class="col-lg-12">
                        <div class="row">
                            <div class="col-lg-12">
                                <div>
                                    <!-- Boostrap-Vue Proposal Table Component-->
                                    <b-table bordered striped hover :items="proposalStages" :fields="headers"></b-table>
                                </div>
                            </div>
                         </div>
                    </div>
                    <br/>
                    <br/>
                    <div class="col-6">
                    <h4 style="text-align: center;">New Bachelor's and Master's Programs with costs over $2M in first five years and Engineering</h4>
                    <div id="amDiv"  style="width: 100%; height: 240px;"></div>
                    </div>
                    <div class="col-6">
                    <ul class="list-group">
                        <li class="list-group-item"><h3>Total Duration in Days: &nbsp;<span v-if="totalDays != 0">{{ totalDays }}</span></h3></li>
                        <li class="list-group-item"><h3>Total Duration in Weeks: &nbsp;<span v-if="totalWeeks != 0">{{ totalWeeks }}</span></h3></li>
                        <li class="list-group-item"><h3>Total Duration in Months: &nbsp;<span v-if="totalMonths != 0">{{ totalMonths }}</span></h3></li>
                        <li class="list-group-item"><h3>Total Duration in Years: &nbsp;<span v-if="totalYears != 0">{{ totalYears }}</span></h3></li>
                    </ul>
                    </div>
            </div>
        </div>
 </div>


<!-- Date-picker dependencies -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.3"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>

<!-- Date-picker itself --> 
<script src="https://cdn.jsdelivr.net/npm/pc-bootstrap4-datetimepicker@4.17/build/js/bootstrap-datetimepicker.min.js"></script>

 
<!-- Vue js -->
<script src="https://cdn.jsdelivr.net/npm/vue@2.5"></script>

  <!-- Vee Validate -->
<script src="https://unpkg.com/vee-validate@latest"></script>

<!-- Date-picker Vue-Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/vue-bootstrap-datetimepicker@5"></script>


<!-- Vue-Bootstrap Js -->
<script src="//unpkg.com/bootstrap-vue@latest/dist/bootstrap-vue.min.js"></script>

<script src="//polyfill.io/v3/polyfill.min.js?features=es2015%2CIntersectionObserver" crossorigin="anonymous"></script>



<!--AMCharts -->
<script src="//www.amcharts.com/lib/4/core.js"></script>
<script src="//www.amcharts.com/lib/4/charts.js"></script>
<script src="//www.amcharts.com/lib/4/themes/animated.js"></script>





<script>
  // Initialize as global component
  Vue.component('date-picker', VueBootstrapDatetimePicker);
</script> 
<script>
   const dict = {
          custom: {
            dateFormatted: {
                required:  'Please enter a date',
                date_format: 'Please enter a date in the form MM/DD/YYYY',
            }
          }
    };
    Vue.use(VeeValidate); // good to go.
</script>
 <script>
    var programCalculator = new Vue({
        el: '#programCalculator',
        created(){
            this.$validator.localize('en', dict);
        },
        mounted(){
            this.showPieChart();
        },
        data() {
          return {
                options: {
                format: 'MM/DD/YYYY',
                useCurrent: false,
              },
              userSubmitted: false,
              displaySummary: false,
              inputDisabled: false,
              message: '',
              header: 'Proposal Calculator',
              date: new Date().toISOString().substr(0, 10),
              dateFormatted: this.formatDate(this.date),
              menu: false,
              modal: false,
              hideFooter: true,
              headers: [
                {label: 'Stage', key: 'stage'},
                {label: 'Submission Date', key: 'submission_date'},
                {label: 'Duration in Days', key: 'duration'},
                {label: 'Date of Completion', key: 'date_of_completion'},
                {label: 'Notes', key: 'notes'},
              ],
              proposal: {
                stages: [
                    {stage: "Academic Programs 1", submission_date: '...', duration: 7, date_of_completion: '...', notes: 'PPR and BPF review'},
                    {stage: "Provost", submission_date: '...', duration: 14, date_of_completion: '...', notes: 'Proposal Review'},
                    {stage: "Provosts' Council 1", submission_date: '...', duration: 30, date_of_completion: '...', notes: 'Monthly'},
                    {stage: "Academic Unit 2", submission_date: '...', duration: 30, date_of_completion: '...', notes: 'Preparation of THECB documentation'},
                    {stage: "Academic Programs 2", submission_date: '...', duration: 14, date_of_completion: '...', notes: 'THECB Documentation Review'},
                    {stage: "UC/GPSC", submission_date: '...', duration: 90, date_of_completion: '...', notes: 'Monthly - Summer Hiatus June - Aug'},
                    {stage: "Provosts' Council 2", submission_date: '...', duration: 30, date_of_completion: '...', notes: 'Monthly'},
                    {stage: "Board of Regents", submission_date: '...', duration: 90, date_of_completion: '...', notes: 'Quarterly'},
                    {stage: "Area Notification", submission_date: '...', duration: 30, date_of_completion: '...', notes: 'Notification of all institutions (50 miles)'},
                    {stage: "THECB", submission_date: '...', duration: 60, date_of_completion: '...', notes: 'THECB Staff Review'},
                    {stage: "THECB CAWS", submission_date: '...', duration: 90, date_of_completion: '...', notes: 'Quarterly'},
                    {stage: "THECB Board Meeting", submission_date: '...', duration: 30, date_of_completion: '...', notes: 'Quarterly'},
                    {stage: "US Department of Education", submission_date: '...' , duration: 45, date_of_completion: '...', notes: 'Financial Aid Eligibility'},
                    {stage: "Academic Unit and UH Stakeholders", submission_date: '...', duration: 14, date_of_completion: '...', notes: 'PeopleSoft implementation, Catalog implementation, Application software updated, program available to applicants'},	
                ],
              },
        
      
              amChartData: [
                    {stage: "Academic Programs 1",  duration: 7},
                    {stage: "Provost",  duration: 14},
                    {stage: "Provosts' Council 1", duration: 30},
                    {stage: "Academic Unit 2", duration: 30},
                    {stage: "Academic Programs 2",  duration: 14},
                    {stage: "UC/GPSC", duration: 90 },
                    {stage: "Provosts' Council 2",  duration: 30},
                    {stage: "Board of Regents",  duration: 90},
                    {stage: "Area Notification",  duration: 30},
                    {stage: "THECB", duration: 60 },
                    {stage: "THECB CAWS", duration: 90 },
                    {stage: "THECB Board Meeting", duration: 30 },
                    {stage: "US Department of Education", duration: 45},
                    {stage: "Academic Unit and UH Stakeholders",  duration: 14},	
              ],

              totalDays: 0,
              totalWeeks: 0,
              totalMonths: 0,
              totalYears: 0, 
          }
        },
        methods: {
           clearTotals(){
            this.totalDays = 0;
            this.totalWeeks = 0;
            this.totalMonths = 0;
            this.totalYears = 0;
           },
           calculateTotals(){
            let submissionDate = moment(this.proposal.stages[0].submission_date);
            let proposalDate = moment(this.proposal.stages[this.proposal.stages.length - 1].date_of_completion);
            this.totalDays = proposalDate.diff(submissionDate, 'days');
            this.totalWeeks = proposalDate.diff(submissionDate, 'weeks');
            this.totalMonths = proposalDate.diff(submissionDate, 'months');
            this.totalYears = proposalDate.diff(submissionDate, 'years');

           },
            showPieChart(){
                let amChart = am4core.create("amDiv", am4charts.PieChart);
                am4core.useTheme(am4themes_animated);
                amChart.data = this.amChartData;
                let pieSeries = amChart.series.push(new am4charts.PieSeries());
                pieSeries.dataFields.value = "duration";
                pieSeries.dataFields.category = "stage";
                pieSeries.labels.template.text = "{category}-  {value.value} days";
                pieSeries.slices.template.tooltipText = "{category}-  {value.value} days";
                pieSeries.slices.template.stroke = am4core.color("#fff");
                pieSeries.slices.template.strokeWidth = 2;
                pieSeries.slices.template.strokeOpacity = 1;
                // This creates initial animation
                pieSeries.hiddenState.properties.opacity = 1;
                pieSeries.hiddenState.properties.endAngle = -90;
            },
            formatDate (date) {
                if (!date) return null
                const [year, month, day] = date.split('-')
                return `${month}/${day}/${year}`
            },
            parseDate (date) {
                if (!date) return null
                const [month, day, year] = date.split('/')
                return `${year}-${month.padStart(2, '0')}-${day.padStart(2, '0')}`
            },

            submitDate(){
                this.$validator.validateAll().then((result) => { 
                 if (result) {
                    if(this.dateFormatted){
                        this.message = "";  
                        this.userSubmitted = true;
                        this.inputDisabled = true;
                        let success = this.calculateDates(this.proposal, this.dateFormatted);
                        if(success){
                        this.displaySummary = true;
                        this.calculateTotals();
                        this.showPieChart();
                       }
                     }    
                } else{
                    console.log('Validation Errors');
                   return false;             
                }   
             });
            },
            clearDates(){
             this.inputDisabled = false;   
             this.dateFormatted = '';
             this.message = "";  
		     this.clearProposal(this.proposal);
             this.userSubmitted = false; 
             this.clearCalulatedDurationForChart();
             this.clearTotals();
             this.showPieChart(); 
             this.displaySummary = false;
            },
           
           clearProposal({stages}){
            let normalDurations = [7, 14, 30, 30, 14, 90, 30, 90, 30, 60, 90, 30, 45, 14];
            for(let i = 0; i < stages.length; i++){
                stages[i]['submission_date'] = '...';
                stages[i]['date_of_completion'] = '...';
                stages[i]['duration'] = normalDurations[i];
            }	
           },

           clearCalulatedDurationForChart(){
            let normalDurations = [7, 14, 30, 30, 14, 90, 30, 90, 30, 60, 90, 30, 45, 14];
            for(let i = 0; i <  this.amChartData.length; i++){
                this.amChartData[i]['duration'] = normalDurations[i];
            }
           },

           adjustDateOnAWeekend(stage, dateData, i, flag){
                //console.log(`Adjustiing Date Data passed in ${JSON.stringify(dateData)}`);
                let alteredDate;
                let day = moment(dateData, 'MM-DD-YYYY').format('dddd');
                console.log(`This date day is on a: ${day}`);
               switch(day){
                 case 'Friday':
                       // console.log(`This is a : ${flag == 1 ? 'submission_date' : flag == 2 ? 'date_of_completion' : 'regular date'}`);
                       // Check the flag og the date and determine the date type
                        if(flag == 1 || flag == 2){
                            // Flag 1: is a submission date
                            // Flag 2: is a date of completion date
                            if(flag == 1){
                                // add the corresponding days
                                    alteredDate = moment(dateData, 'MM-DD-YYYY').add(0, 'days').format('l');
                                    stage[i]['submission_date'] = alteredDate;
                            }
                            if(flag == 2){
                                  // add the corresponding days
                                alteredDate = moment(dateData, 'MM-DD-YYYY').add(0, 'days').format('l');
                                stage[i]['date_of_completion'] = alteredDate;
                            }
                            // set the duration 
                            stage[i]['duration'] = (parseInt(stage[i]['duration']) + 0);
                            // populate the chart  with updated durations 
                            this.amChartData[i]['duration'] = stage[i]['duration'];
                        } else {
                            // This is a regular date
                            alteredDate = moment(dateData, 'MM-DD-YYYY').add(0, 'days').format('l');
                            // set the duration 
                            stage[i]['duration'] = (parseInt(stage[i]['duration']) + 0);
                              // populate the chart  with updated durations 
                            this.amChartData[i]['duration'] = stage[i]['duration'];
                        }
			    break;
			    case 'Saturday':
                  // console.log(`This is a : ${flag == 1 ? 'submission_date' : flag == 2 ? 'date_of_completion' : 'regular date'}`);
                    if(flag == 1 || flag == 2){
                         // Flag 1: is a submission date
                        // Flag 2: is a date of completion date
                            if(flag == 1){
                                 // add the corresponding days
                                    alteredDate = moment(dateData, 'MM-DD-YYYY').add(2, 'days').format('l');
                                    stage[i]['submission_date'] = alteredDate;
                            }
                            if(flag == 2){
                                 // add the corresponding days
                                    alteredDate = moment(dateData, 'MM-DD-YYYY').add(2, 'days').format('l');
                                    stage[i]['date_of_completion'] = alteredDate;
                            }
                            // set the duration 
                            stage[i]['duration'] = (parseInt(stage[i]['duration']) + 2);
                             // populate the chart  with updated durations 
                             this.amChartData[i]['duration'] = stage[i]['duration'];
                        }  else {
                        // This is a regular date
                            alteredDate = moment(dateData, 'MM-DD-YYYY').add(2, 'days').format('l');
                             // set the duration 
                            stage[i]['duration'] = (parseInt(stage[i]['duration']) + 2);
                             // populate the chart  with updated durations 
                             this.amChartData[i]['duration'] = stage[i]['duration'];
                        }
			    break;
			    case 'Sunday':
                       // console.log(`This is a : ${flag == 1 ? 'submission_date' : flag == 2 ? 'date_of_completion' : 'regular date'}`);
                            if(flag == 1 || flag == 2){
                            // Flag 1: is a submission date
                            // Flag 2: is a date of completion date
                                if(flag == 1){
                                      // add the corresponding days
                                    alteredDate = moment(dateData, 'MM-DD-YYYY').add(1, 'days').format('l');
                                    stage[i]['submission_date'] = alteredDate;
                                }
                                if(flag == 2){
                                      // add the corresponding days
                                    alteredDate = moment(dateData, 'MM-DD-YYYY').add(1, 'days').format('l');
                                     stage[i]['date_of_completion'] = alteredDate;
                            }
                            stage[i]['duration'] = (parseInt(stage[i]['duration']) + 1);    
                            this.amChartData[i]['duration'] = stage[i]['duration'];
                            }  else {
                                // This is a regular date
                                alteredDate = moment(dateData, 'MM-DD-YYYY').add(1, 'days').format('l');
                                // set the duration 
                                stage[i]['duration'] = (parseInt(stage[i]['duration']) + 1);
                                  // populate the chart  with updated durations 
                            this.amChartData[i]['duration'] = stage[i]['duration'];
                            }
			    break;
                default:
                         // date is on a weekday return date
                        alteredDate = dateData;
                        // set the duration 
                        this.amChartData[i]['duration'] = stage[i]['duration'];
                    break;
		        } // End of Switch

                return alteredDate;
           },

           setNormalDates(stage, i){
           // console.log(`Object iterating ${JSON.stringify(stage, null, 2)}`);
           let previousStageDateOfcompletion = stage[i - 1]['date_of_completion'];
            stage[i]['submission_date'] =  previousStageDateOfcompletion;
            let currentStageSubmissionDate = this.adjustDateOnAWeekend(stage, stage[i]['submission_date'], i, 1);
            let currentDateOfCompletion =  moment(currentStageSubmissionDate, 'MM-DD-YYYY').add(stage[i]['duration'], 'days').format('l');
            stage[i]['date_of_completion'] = this.adjustDateOnAWeekend(stage, currentDateOfCompletion, i, 2);
            console.log(`Adding ${stage[i]['duration']} days to ${stage[i]['submission_date']} to get ${currentDateOfCompletion}`);
           
           },


           isOnAHiatus(currentMonthIndex){
            //Returns true if the index gathered from date the user entered is between Jun to Aug   
            return currentMonthIndex  > 4  && currentMonthIndex < 8 ? true: false;
           },

           hiatusDateAdjustment (yearUserChose, stage, i) {
                /**
                *   This function adjusts the proposal date calculation at the stage 'UC/GPSC'
                *   It also checks the previous stage's date of completion and tests if it after March 1st deadline
                *   it takes as arguments the stage array, the date user entered, and the current iteration index
                *****/
                let previousStageDateOfCompletion  = this.adjustDateOnAWeekend(stage, stage[i - 1]['date_of_completion'], i, 2);
                let yearOfPrvDateOfCompletion = moment(previousStageDateOfCompletion)._d.getFullYear();
                let year = moment(previousStageDateOfCompletion).isAfter(yearUserChose) ? yearOfPrvDateOfCompletion.toString() : yearUserChose;
                let marchDeadline = this.adjustDateOnAWeekend(stage, '3/1/' + year, i, 3);
                console.log(`Hiatus Year value is: ${year} Previous Year is ${yearOfPrvDateOfCompletion.toString()} and Year user chose is ${yearUserChose}`);
                if(moment(previousStageDateOfCompletion ,'MM-DD-YYYY').isAfter(marchDeadline)){
                    // Check if the date previous stage's date of completion is between march and aug
                    if(moment(previousStageDateOfCompletion, 'MM-DD-YYYY').isBetween('3/2/'+year, '8/31/'+year)){
                         // If the date previous stage's date of completion is between march and aug
                         // Set the monthly meeting submission date to Sep
                        stage[i]['submission_date'] = this.adjustDateOnAWeekend(stage, '9/1/'+ year, i, 1);
                        let currentStageSubmissionDate = moment(stage[i]['submission_date'], 'MM-DD-YYYY').add(stage[i]['duration'], 'days').format('l');
                        stage[i]['date_of_completion'] = this.adjustDateOnAWeekend(stage, currentStageSubmissionDate, i, 2);
                    }
                    if(moment(previousStageDateOfCompletion).isAfter('8/31/'+ year)){
                         // If the date previous stage's date of completion is after aug
                        // Calculate dates normally
                        stage[i]['submission_date'] =  this.adjustDateOnAWeekend(stage, stage[i - 1]['date_of_completion'], i, 1);
                        let currentDateOfCompletion  = moment(stage[i]['submission_date'], 'MM-DD-YYYY').add(stage[i]['duration'], 'days').format('l');
                        stage[i]['date_of_completion']   = this.adjustDateOnAWeekend(stage, currentDateOfCompletion, i , 2);
                    }
                }
		
		
            },


            dateAdjustmentForBoardOfRegentsMeeting(stage, yearUserChose, i){
  	         /**
             *  This function adjusts the proposal date calculation at the stage 'Board of Regents'
             *  The function check the previous stage's date of completion and tests if it is sixty days
             *  prior to the quaterly board meeting
             *  it takes as arguments the stage array, the date user entered, and the current iteration index
             **/
                //Get the previous stage's date of completion
                let previousStageDateOfCompletion  = stage[i - 1]['date_of_completion'];
                // Get the full Year of the previous stage's date of completion
                let yearOfPrvDateOfCompletion = moment(previousStageDateOfCompletion)._d.getFullYear();
                console.log(`Prv date of completion ${yearOfPrvDateOfCompletion.toString()}`);
                // Get the month index of the previous stage's date of completion       
                let  monthIndex = moment(previousStageDateOfCompletion)._d.getMonth();
                let  month  =  moment(previousStageDateOfCompletion).format("MMM");
                // Check if the previous stage's date of completion is after year user entered if it is then return that year if its not then return the year the user entered
                let year = moment(previousStageDateOfCompletion).isAfter(yearUserChose) ? yearOfPrvDateOfCompletion.toString() : yearUserChose;
                // Get the month  ex. Feb   
                console.log(`BoardOfRegentsMeeting Year value is: ${year} Previous Year is ${yearOfPrvDateOfCompletion.toString()} and Year user chose is ${yearUserChose}`);
                switch(month){
                    case 'Feb':
                    //Check if the previous stage's date of completion month is in Feb
                    let febBoardMonth = this.adjustDateOnAWeekend(stage, '2/1/'+ year, i, 3);
                    //Calculate sixty days before the first of the board meeting month and get that date
                    let sixtyDaysBeforeFeb = moment(febBoardMonth, 'MM-DD-YYYY').subtract(60, 'days').format('l');
                    //Check if the previous stage's date of completion is before the date that is sixty days before the board meeting month
                    if(moment(previousStageDateOfCompletion, 'MM-DD-YYYY').isBefore(sixtyDaysBeforeFeb)){
                        // If previous stage's date of completion IS PRIOR to that date that is 60days before board meeting 
                        // then calculate dates normally
                        stage[i]['submission_date']  = this.adjustDateOnAWeekend(stage, stage[i - 1]['date_of_completion'], i, 1);
                        let currentDateOfCompletion = moment(stage[i]['submission_date'], 'MM-DD-YYYY').add(stage[i]['duration'], 'days').format('l');
                        stage[i]['date_of_completion'] = this.adjustDateOnAWeekend(stage, currentDateOfCompletion, i, 2);
                    } else {
                        // If previous stage's date of completion IS NOT PRIOR to that date that is 60 days before board meeting 
                        // then jump to next board meeting month
                        stage[i]['submission_date']  = this.adjustDateOnAWeekend(stage, '5/1/'+ year, i, 1);
                        let currentDateOfCompletion = moment(stage[i]['submission_date'], 'MM-DD-YYYY').add(stage[i]['duration'], 'days').format('l');
                        stage[i]['date_of_completion']  =  this.adjustDateOnAWeekend(stage, currentDateOfCompletion, i, 2);
                    }
                    break;
                    case 'May':
                    //Check if the previous stage's date of completion month is in May
                    let mayBoardMonth = this.adjustDateOnAWeekend(stage, '5/1/'+ year, i, 3);
                    //Calculate sixty days before the first of the board meeting month and get that date
                    let sixtyDaysBeforeMay = moment(mayBoardMonth,'MM-DD-YYYY').subtract(60, 'days').format('l');
                    //Check if the previous stage's date of completion is before the date that is sixty days before the board meeting month
                    if(moment(previousStageDateOfCompletion, 'MM-DD-YYYY').isBefore(sixtyDaysBeforeMay)){
                        // If previous stage's date of completion IS PRIOR to that date that is 60days before board meeting 
                        // then calculate dates normally
                    stage[i]['submission_date']  = stage[i - 1]['date_of_completion'];
                    stage[i]['date_of_completion'] = moment(stage[i]['submission_date'], 'MM-DD-YYYY').add(stage[i]['duration'], 'days').format('l');
                    } else {
                        // If previous stage's date of completion  IS NOT PRIOR to that date that is 60 days before board meeting 
                        // then jump to next board meeting month
                        stage[i]['submission_date']  = this.adjustDateOnAWeekend(stage,'8/1/'+ year, i, 1);
                        let currentDateOfCompletion  = moment(stage[i]['submission_date'], 'MM-DD-YYYY').add(stage[i]['duration'], 'days').format('l');
                        stage[i]['date_of_completion'] = this.adjustDateOnAWeekend(stage, currentDateOfCompletion, i, 2);
                    }
				break;
				case 'Aug':
                 //Check if the previous stage's date of completion month is in Aug
				let augustBoardMonth = this.adjustDateOnAWeekend(stage, '8/1/'+ year, i, 3);
                //Calculate sixty days before the first of the board meeting month and get that date
				let sixtyDaysBeforeAugust = moment(augustBoardMonth, 'MM-DD-YYYY').subtract(60, 'days').format('l');
                //Check if the previous stage's date of completion is before the date that is sixty days before the board meeting month
                if(moment(previousStageDateOfCompletion, 'MM-DD-YYYY').isBefore(sixtyDaysBeforeAugust)){
                    // If previous stage's date of completion IS PRIOR to that date that is 60 days before board meeting 
                    // then calculate dates normally
                    stage[i]['submission_date']  = this.adjustDateOnAWeekend(stage, stage[i - 1]['date_of_completion'], i, 1);
                    let currentDateOfCompletion  = moment(stage[i]['submission_date'], 'MM-DD-YYYY').add(stage[i]['duration'], 'days').format('l');
                    stage[i]['date_of_completion'] =  this.adjustDateOnAWeekend(stage, currentDateOfCompletion, i, 2);
                } else {
                     // If previous stage's date of completion  IS NOT PRIOR to that date that is 60 days before board meeting 
                    // then jump to next board meeting month
                    stage[i]['submission_date']  = this.adjustDateOnAWeekend(stage, '11/1/'+ year,i, 3);
                    let currentDateOfCompletion  = moment(stage[i]['submission_date'], 'MM-DD-YYYY').add(stage[i]['duration'], 'days').format('l');
                    stage[i]['date_of_completion'] = this.adjustDateOnAWeekend(stage, currentDateOfCompletion, i, 2);
                }
				break;
				case 'Nov':
                //Check if the previous stage's date of completion month is in Nov
				let novemberBoardMonth = this.adjustDateOnAWeekend(stage, '11/1/'+ year, i, 3);
                  //Calculate sixty days before the first of the board meeting month and get that date
				let sixtyDaysBeforeNovember = moment(novemberBoardMonth, 'MM-DD-YYYY').subtract(60, 'days').format('l');
                //Check if the previous stage's date of completion is before the date that is sixty days before the board meeting month
                if(moment(previousStageDateOfCompletion, 'MM-DD-YYYY').isBefore(sixtyDaysBeforeNovember)){
                      // If previous stage's date of completion IS PRIOR to that date that is 60days before board meeting 
                    // then calculate dates normally
                    stage[i]['submission_date']  = this.adjustDateOnAWeekend(stage, stage[i - 1]['date_of_completion'], i, 1);
                    let currentDateOfCompletion  =  moment(stage[i]['submission_date'], 'MM-DD-YYYY').add(stage[i]['duration'], 'days').format('l');
                    stage[i]['date_of_completion'] = this.adjustDateOnAWeekend(stage, currentDateOfCompletion, i, 2);
                } else {
                    // If previous stage's date of completion  IS NOT PRIOR to that date that is 60 days before board meeting 
                    // then jump to next board meeting month
                    stage[i]['submission_date']  = this.adjustDateOnAWeekend(stage,'2/1/'+ (parseInt(year) + 1), i, 1);
                    let currentDateOfCompletion  = moment(stage[i]['submission_date'], 'MM-DD-YYYY').add(stage[i]['duration'], 'days').format('l');
                    stage[i]['date_of_completion'] = this.adjustDateOnAWeekend(stage, currentDateOfCompletion, i, 2);
                }
				break;
				default:
                    	break;
			} // end of switch
	    },

        calculateDates({stages}, date){
                var currentMonthIndex  = moment(date)._d.getMonth();
                console.log(`Month index ${currentMonthIndex}`);
                 console.log(`Proposal Arr passed in ${JSON.stringify(stages, null, 2)}`);
                var chosenYear = moment(date, 'YYYY')._d.getFullYear().toString();
                let currentDateToday = moment().format('l');
                let userInput = moment(date, 'MM-DD-YYYY').format('l');
                console.log(`Date User selected ${moment(date).format('l')}`);
                let isBeforeToday = moment(userInput, 'MM-DD-YYYY').isBefore(new Date(currentDateToday).toISOString());
                if(!isBeforeToday){
                    for(let i = 0; i < stages.length; i++){
                        if(i < 1){
                                // This will be user's input
                                stages[i]['submission_date'] = this.adjustDateOnAWeekend(stages, userInput, i, 1);	
                                let currentSubmissionDate = moment(stages[i]['submission_date'], 'MM-DD-YYYY').add(stages[i]['duration'], 'days').format('l');
                                let currentDateOfCompletion = stages[i]['date_of_completion'];
                                stages[i]['date_of_completion'] = this.adjustDateOnAWeekend(stages, currentSubmissionDate, i, 2);
                                console.log(`Adding ${stages[i]['duration']} days to ${stages[i]['submission_date']} to get ${currentDateOfCompletion}`);
                                this.amChartData[i]['duration'] = stages[i]['duration'];
                        } 
                        else {
                            this.setNormalDates(stages, i);
                            if(this.isOnAHiatus(currentMonthIndex) && stages[i]['stage'] === "UC/GPSC"){
                                this.hiatusDateAdjustment(chosenYear, stages, i);
                            }
                            if(stages[i]['stage'] === "Board of Regents"){
                                this.dateAdjustmentForBoardOfRegentsMeeting(stages, chosenYear, i);
                            }
                            if(stages[i]['stage'] === "THECB CAWS"){
                                this.adjustDateForCAWSQuarterly(stages, chosenYear, i);
                            }
                            if(stages[i]['stage'] === "THECB Board Meeting"){
                                this.adjustDateForCAWSBoardMeetingQuarterly(stages, chosenYear, i);
                            }
                        }
                    }
                    
                    console.log(`Proposal 2: Poulated ${JSON.stringify(this.proposal, null, 2)}`);
                   return true; // function is done executing return true
                /*  console.log(`Proposal 2: Poulated ${JSON.stringify(proposalTwo, null, 2)}`);
                console.log(`Proposal 3: Poulated ${JSON.stringify(proposalThree, null, 2)}`);  */
                }   else {
                      // User entered Date in the past
                        console.log('You entered a date in the past!');
                        this.message = "You entered a date in the past! Please Try Again...";
                        return false;
                }
         },


         adjustDateForCAWSQuarterly(stage, yearUserChose, i){
            let previousStageDateOfCompletion  = stage[i - 1]['date_of_completion'];
            let yearOfPrvDateOfCompletion = moment(previousStageDateOfCompletion)._d.getFullYear();
            console.log(`Prv date of completion ${yearOfPrvDateOfCompletion.toString()}`);
            let year = moment(previousStageDateOfCompletion).isAfter(yearUserChose) ? yearOfPrvDateOfCompletion.toString() : yearUserChose;
            console.log(`BoardOfRegentsMeeting Year value is: ${year} Previous Year is ${yearOfPrvDateOfCompletion.toString()} and Year user chose is ${yearUserChose}`);
            let  month  =  moment(previousStageDateOfCompletion).format("MMM");
            console.log(`Month passed in ${month}`);
                switch(month){
                    case 'Mar':
                        let marBoardMonth = this.adjustDateOnAWeekend(stage, '3/1/'+ year, i, 3);
                        let sixtyDaysBeforeMar = moment(marBoardMonth, 'MM-DD-YYYY').subtract(60, 'days').format('l');
                        if(moment(previousStageDateOfCompletion).isBefore(sixtyDaysBeforeMar)){
                        stage[i]['submission_date']  = stage[i - 1]['date_of_completion'];
                        stage[i]['date_of_completion'] = moment(stage[i]['submission_date'], 'MM-DD-YYYY').add(stage[i]['duration'], 'days').format('l');
                        } else {
                            stage[i]['submission_date']  = this.adjustDateOnAWeekend(stage,'6/1/'+ year, i, 1);
                            let currentDateOfCompletion  = moment(stage[i]['submission_date'], 'MM-DD-YYYY').add(stage[i]['duration'], 'days').format('l');
                            stage[i]['date_of_completion'] = this.adjustDateOnAWeekend(stage, currentDateOfCompletion, i, 2);
                        }
                    break;
                    case 'Jun':
                    let junBoardMonth = this.adjustDateOnAWeekend(stage, '6/1/'+ year, i, 3);
                    let sixtyDaysBeforeJun = moment(junBoardMonth, 'MM-DD-YYYY').subtract(60, 'days').format('l');
                    // check if prev date of completion is before jun meeting
                    if(moment(previousStageDateOfCompletion).isBefore(sixtyDaysBeforeJun)){
                        stage[i]['submission_date']  = stage[i - 1]['date_of_completion'];  
                        stage[i]['date_of_completion'] = moment(stage[i]['submission_date'], 'MM-DD-YYYY').add(stage[i]['duration'], 'days').format('l');
                    } else {
                        //Jump to next board meeting month 
                        stage[i]['submission_date']  = this.adjustDateOnAWeekend(stage,'9/1/'+ year, i, 1);
                        let currentDateOfCompletion  = moment(stage[i]['submission_date'], 'MM-DD-YYYY').add(stage[i]['duration'], 'days').format('l');
                        stage[i]['date_of_completion'] = this.adjustDateOnAWeekend(stage, currentDateOfCompletion, i, 2);    
                    }
                    break;
                    case 'Sep':
                    let sepBoardMonth = this.adjustDateOnAWeekend(stage, '9/1/'+ year, i, 3);
                    let sixtyDaysBeforeSep = moment(sepBoardMonth, 'MM-DD-YYYY').subtract(60, 'days').format('l');
                    // check if prev date of completion is before jun meeting
                    if(moment(previousStageDateOfCompletion).isBefore(sixtyDaysBeforeSep)){
                        stage[i]['submission_date']  = stage[i - 1]['date_of_completion'];  
                        stage[i]['date_of_completion'] = moment(stage[i]['submission_date'], 'MM-DD-YYYY').add(stage[i]['duration'], 'days').format('l');
                    } else {
                        //Jump to next board meeting month 
                        stage[i]['submission_date']  = this.adjustDateOnAWeekend(stage,'12/1/'+ year, i, 1);
                        let currentDateOfCompletion  = moment(stage[i]['submission_date']).add(stage[i]['duration'], 'days').format('l');
                        stage[i]['date_of_completion'] = this.adjustDateOnAWeekend(stage, currentDateOfCompletion, i, 2);    
                    }
                    break;
                    case 'Dec':
                    let decBoardMonth = this.adjustDateOnAWeekend(stage, '12/1/'+ year, i, 3);
                    let sixtyDaysBeforeDec = moment(decBoardMonth, 'MM-DD-YYYY').subtract(60, 'days').format('l');
                      // check if prev date of completion is before jun meeting
                        if(moment(previousStageDateOfCompletion).isBefore(sixtyDaysBeforeDec)){
                            stage[i]['submission_date']  = this.adjustDateOnAWeekend(stage, stage[i - 1]['date_of_completion'], i, 1);
                            let currentDateOfCompletion  =  moment(stage[i]['submission_date'], 'MM-DD-YYYY').add(stage[i]['duration'], 'days').format('l');
                            stage[i]['date_of_completion'] = this.adjustDateOnAWeekend(stage, currentDateOfCompletion, i, 2);
                        } else {
                             //Jump to next board meeting month 
                            stage[i]['submission_date']  = this.adjustDateOnAWeekend(stage,'3/1/'+ (parseInt(year) + 1), i, 1);
                            let currentDateOfCompletion  = moment(stage[i]['submission_date'], 'MM-DD-YYYY').add(stage[i]['duration'], 'days').format('l');
                            stage[i]['date_of_completion'] = this.adjustDateOnAWeekend(stage, currentDateOfCompletion, i, 2);
                        }
                    break;
                }
         },

         adjustDateForCAWSBoardMeetingQuarterly(stage, yearUserChose, i){
            let previousStageDateOfCompletion  = stage[i - 1]['date_of_completion'];
            let yearOfPrvDateOfCompletion = moment(previousStageDateOfCompletion)._d.getFullYear();
            console.log(`Prv date of completion ${yearOfPrvDateOfCompletion.toString()}`);
            let year = moment(previousStageDateOfCompletion).isAfter(yearUserChose) ? yearOfPrvDateOfCompletion.toString() : yearUserChose;
            console.log(`BoardOfRegentsMeeting Year value is: ${year} Previous Year is ${yearOfPrvDateOfCompletion.toString()} and Year user chose is ${yearUserChose}`);
            let  month  =  moment(previousStageDateOfCompletion).format("MMM");
                switch(month){
                    case 'Jan':
                    let janBoardMonth = this.adjustDateOnAWeekend(stage, '1/1/'+year, i, 3);
                    let sixtyDaysBeforeJan = moment(janBoardMonth, 'MM-DD-YYYY').subtract(60, 'days').format('l');
                      // check if prev date of completion is before jun meeting
                    if(moment(previousStageDateOfCompletion).isBefore(sixtyDaysBeforeJan)){
                        stage[i]['submission_date']  = this.adjustDateOnAWeekend(stage, stage[i - 1]['date_of_completion'], i, 1);
                        let currentDateOfCompletion  =  moment(stage[i]['submission_date'], 'MM-DD-YYYY').add(stage[i]['duration'], 'days').format('l');
                        stage[i]['date_of_completion'] = this.adjustDateOnAWeekend(stage, currentDateOfCompletion, i, 2);
                    } else {
                         // Jump to next board meeting month 
                        stage[i]['submission_date']  = this.adjustDateOnAWeekend(stage,'4/1/'+ year, i, 1);
                        let currentDateOfCompletion  = moment(stage[i]['submission_date'], 'MM-DD-YYYY').add(stage[i]['duration'], 'days').format('l');
                        stage[i]['date_of_completion'] = this.adjustDateOnAWeekend(stage, currentDateOfCompletion, i, 2);    
                    }
                    break;
                    case 'Apr':
                    let aprBoardMonth = this.adjustDateOnAWeekend(stage, '4/1/'+ year, i, 3);
                    let sixtyDaysBeforeApr = moment(aprBoardMonth, 'MM-DD-YYYY').subtract(60, 'days').format('l');
                      // check if prev date of completion is before jun meeting
                    if(moment(previousStageDateOfCompletion).isBefore(sixtyDaysBeforeApr)){
                        stage[i]['submission_date']  = this.adjustDateOnAWeekend(stage, stage[i - 1]['date_of_completion'], i, 1);
                        let currentDateOfCompletion  =  moment(stage[i]['submission_date'], 'MM-DD-YYYY').add(stage[i]['duration'], 'days').format('l');
                        stage[i]['date_of_completion'] = this.adjustDateOnAWeekend(stage, currentDateOfCompletion, i, 2);
                    } else {
                         // Jump to next board meeting month 
                        stage[i]['submission_date']  = this.adjustDateOnAWeekend(stage,'7/1/'+ year, i, 1);
                        let currentDateOfCompletion  = moment(stage[i]['submission_date'], 'MM-DD-YYYY').add(stage[i]['duration'], 'days').format('l');
                        stage[i]['date_of_completion'] = this.adjustDateOnAWeekend(stage, currentDateOfCompletion, i, 2);    
                    }
                    break;
                    case 'Jul':
                    let julBoardMonth = this.adjustDateOnAWeekend(stage, '7/1/'+ year, i, 3);
                    let sixtyDaysBeforeJul = moment(julBoardMonth, 'MM-DD-YYYY').subtract(60, 'days').format('l');
                      // check if prev date of completion is before jun meeting
                    if(moment(previousStageDateOfCompletion).isBefore(sixtyDaysBeforeJul)){
                        stage[i]['submission_date']  = this.adjustDateOnAWeekend(stage, stage[i - 1]['date_of_completion'], i, 1);
                        let currentDateOfCompletion  =  moment(stage[i]['submission_date'], 'MM-DD-YYYY').add(stage[i]['duration'], 'days').format('l');
                        stage[i]['date_of_completion'] = this.adjustDateOnAWeekend(stage, currentDateOfCompletion, i, 2);
                    } else {
                         // Jump to next board meeting month 
                        stage[i]['submission_date']  = this.adjustDateOnAWeekend(stage,'11/1/'+ year, i, 1);
                        let currentDateOfCompletion  = moment(stage[i]['submission_date'], 'MM-DD-YYYY').add(stage[i]['duration'], 'days').format('l');
                        stage[i]['date_of_completion'] = this.adjustDateOnAWeekend(stage, currentDateOfCompletion, i, 2);    
                    }
                    break;
                    case 'Nov':
                    let novBoardMonth = this.adjustDateOnAWeekend(stage, '11/1/'+ year, i, 3);
                    let sixtyDaysBeforeNov = moment(novBoardMonth, 'MM-DD-YYYY').subtract(60, 'days').format('l');
                      // check if prev date of completion is before jun meeting
                    if(moment(previousStageDateOfCompletion).isBefore(sixtyDaysBeforeNov)){
                        stage[i]['submission_date']  = this.adjustDateOnAWeekend(stage, stage[i - 1]['date_of_completion'], i, 1);
                        let currentDateOfCompletion  =  moment(stage[i]['submission_date'], 'MM-DD-YYYY').add(stage[i]['duration'], 'days').format('l');
                        stage[i]['date_of_completion'] = this.adjustDateOnAWeekend(stage, currentDateOfCompletion, i, 2);
                    } else {
                         // Jump to next board meeting month 
                        stage[i]['submission_date']  = this.adjustDateOnAWeekend(stage,'1/1/'+ (parseInt(year) + 1), i, 1);
                        let currentDateOfCompletion  = moment(stage[i]['submission_date'], 'MM-DD-YYYY').add(stage[i]['duration'], 'days').format('l');
                        stage[i]['date_of_completion'] = this.adjustDateOnAWeekend(stage, currentDateOfCompletion, i, 2);
                    }
                    break;
                }
         },

            
       },
      computed: {
            computedDateFormatted () {
                return this.formatDate(this.date)
            },
            proposalStages(){
                return this.proposal.stages;
            }
       },
      watch: {
        date (val) {
            this.dateFormatted = this.formatDate(this.date)
        },
    
      },
    })
   </script>