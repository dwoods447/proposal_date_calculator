<?php


?>

  <!-- <link href="https://cdn.jsdelivr.net/npm/@mdi/font@3.x/css/materialdesignicons.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/vuetify@2.x/dist/vuetify.min.css" rel="stylesheet"> -->
    <style>
        @import url("https://cdn.jsdelivr.net/npm/@mdi/font@3.x/css/materialdesignicons.min.css");
    </style>
    <style>
        @import url("https://cdn.jsdelivr.net/npm/vuetify@2.x/dist/vuetify.min.css");
    </style>
    <div id="programCalculator">
    <v-app>
        <v-container>
        <v-content>
        <v-layout wrap>
                <v-flex xs12 sm12 md12>
                <v-form>
                <v-layout wrap>
                    <v-flex xs12 sm12 md8 pa-1>
                        <v-menu
                            ref="menu"
                            v-model="menu"
                            :close-on-content-click="false"
                            transition="scale-transition"
                            offset-y
                            full-width
                            max-width="290px"
                            min-width="290px"
                            >
                            <template v-slot:activator="{ on }">
                                <v-text-field
                                v-model="dateFormatted"
                                label="Enter Your Proposal Submission Date"
                                hint="MM/DD/YYYY format"
                                persistent-hint
                                outlined
                                :disabled="inputDisabled"
                                @blur="date = parseDate(dateFormatted)"
                                v-on="on"
                                ></v-text-field>
                            </template>
                            <v-date-picker v-model="date" @input="menu = false" color="red darken-4"></v-date-picker>
                        </v-menu>
                    </v-flex>
                    <v-flex xs12 sm12 md2 pa-1 v-if="!userSubmitted">
                        <v-btn dark depressed color="red darken-4" style="width: 100%; height: 56px;"@click="submitDate">Calculate</v-btn>
                    </v-flex>
                    <v-flex xs12 sm12 md2 pa-1>
                        <v-btn dark depressed color="red darken-4" style="width: 100%; height: 56px;" @click="clearDates">Clear</v-btn>
                    </v-flex>
                   
                    </v-layout>
                    <v-layout row wrap>
                    <v-flex xs12 sm12 md12 v-if="message">
                        <p style="color: red; margin-left: 20px;">{{ message }}</p>
                    </v-flex>
                    </v-layout>
              </v-layout>
                </v-form>
                </v-flex>
                <v-flex xs12 sm12 md12>
                    <v-data-table
                    :headers="headers"
                    :items="proposalStages"
                     class="elevation-1"
                     :items-per-page="15"
                     :hide-default-footer="hideFooter"
                     disable-sort
                    ></v-data-table>
                </v-flex>
        </v-content>
        <br/>
        <br/>
        <div>
        <v-layout wrap>
            <v-flex xs12 sm6 md6 style="width: 100%;" pa-1>
                        <div id="chartContainer" style="height: 360px; width: 100%;"></div>
            </v-flex>
            <v-flex xs12 sm6 md6 pa-1>
                        <v-card>
                            <v-list-item>
                                <v-list-item-content>
                                    <v-list-item-title><h2>Total Duration in Days: &nbsp;{{ totalDays }}</h2></v-list-item-title>
                                </v-list-item-content>
                            </v-list-item>
                            <v-divider></v-divider>
                            <v-list-item>
                                <v-list-item-content>
                                    <v-list-item-title><h2>Total Duration in Weeks: &nbsp;{{ totalWeeks }}</h2></v-list-item-title>
                                </v-list-item-content>
                            </v-list-item>
                            <v-divider></v-divider>
                            <v-list-item>
                                <v-list-item-content>
                                    <v-list-item-title><h2>Total Duration in Months: &nbsp;{{ totalMonths }}</h2></v-list-item-title>
                                </v-list-item-content>
                            </v-list-item>
                            <v-divider></v-divider>
                            <v-list-item>
                                <v-list-item-content>
                                    <v-list-item-title><h2>Total Duration in Years: &nbsp;{{ totalYears }}<span v-if="this.totalMonths > 12">+</span></h2></v-list-item-title>
                                </v-list-item-content>
                            </v-list-item>
                            <v-divider></v-divider>
                        </v-card>
            </v-flex>
        </v-layout>
        </div>
        </v-container>
    </v-app>
   </div>
   <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
   <script src="https://cdn.jsdelivr.net/npm/vee-validate@latest/dist/vee-validate.js"></script>
   <script src="https://cdn.jsdelivr.net/npm/vuetify@2.x/dist/vuetify.js"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
   <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
   <script>
    var programCalculator = new Vue({
        el: '#programCalculator',
        vuetify: new Vuetify(),
        mounted(){
            this.showPieChart();
        },
        data() {
          return {
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
                {text: 'Stages', value: 'name'},
                {text: 'Submission Date', value: 'submission_date'},
                {text: 'Duration in Days', value: 'duration'},
                {text: 'Date of Completion', value: 'date_of_completion'},
                {text: 'Notes', value: 'notes'},
              ],
              proposal: {
                stages: [
                    {name: "Academic Programs 1", submission_date: 'n/a', duration: 7, date_of_completion: 'n/a', notes: 'PPR and BPF review'},
                    {name: "Provost", submission_date: 'n/a', duration: 14, date_of_completion: 'n/a', notes: 'Proposal Review'},
                    {name: "Provosts' Council 1", submission_date: 'n/a', duration: 30, date_of_completion: 'n/a', notes: 'Monthly'},
                    {name: "Academic Unit 2", submission_date: 'n/a', duration: 30, date_of_completion: 'n/a', notes: 'Preparation of THECB documentation'},
                    {name: "Academic Programs 2", submission_date: 'n/a', duration: 14, date_of_completion: 'n/a', notes: 'THECB Documentation Review'},
                    {name: "UC/GPSC", submission_date: 'n/a', duration: 90, date_of_completion: 'n/a', notes: 'Monthly - Summer Hiatus June - Aug'},
                    {name: "Provosts' Council 2", submission_date: 'n/a', duration: 30, date_of_completion: 'n/a', notes: 'Monthly'},
                    {name: "Board of Regents", submission_date: 'n/a', duration: 90, date_of_completion: 'n/a', notes: 'Quarterly'},
                    {name: "Area Notification", submission_date: 'n/a', duration: 30, date_of_completion: 'n/a', notes: 'Notification of all institutions (50 miles)'},
                    {name: "THECB", submission_date: 'n/a', duration: 60, date_of_completion: 'n/a', notes: 'THECB Staff Review'},
                    {name: "THECB Site Review", submission_date: 'n/a', duration: 60, date_of_completion: 'n/a', notes: 'THECB Site Review'},
                    {name: "THECB CAWS", submission_date: 'n/a', duration: 90, date_of_completion: 'n/a', notes: 'Quarterly'},
                    {name: "THECB Board Meeting", submission_date: 'n/a', duration: 30, date_of_completion: 'n/a', notes: 'Quarterly'},
                    {name: "US Department of Education", submission_date: 'n/a' , duration: 45, date_of_completion: 'n/a', notes: 'Financial Aid Eligibility'},
                    {name: "Academic Unit and UH Stakeholders", submission_date: 'n/a', duration: 14, date_of_completion: 'n/a', notes: 'PeopleSoft implementation, Catalog implementation, Application software updated, program available to applicants)'},	
                ],
              },
              chart : null,
              chartOptions: {
                    animationEnabled: true,
                    title: {
                    text: "New Doctoral Programs",
                },
                data: [{
                    type: "pie",
                    startAngle: 240,
                    yValueFormatString: "##0 \"days\"",
                    indexLabel: "{label} {y}",
                        dataPoints: [
                            { label: "Academic Programs 1", y: 7 },
                            { label: "Provost", y: 14 },
                            { label: "Provosts' Council 1", y: 30 },
                            { label: "Academic Unit 2", y: 30 },
                            { label: "Academic Programs 2", y: 14 },
                            { label: "UC/GPSC", y: 90 },
                            { label: "Provosts' Council 2", y: 30 },
                            { label: "Board of Regents", y: 90 },
                            { label: "Area Notification", y: 30 },
                            { label: "THECB", y: 60 },
                            {label:  "THECB Site Review", y: 60},
                            { label: "THECB CAWS", y: 90 },
                            { label: "THECB Board Meeting", y: 30 },
                            { label: "US Department of Education", y: 45 },
                            { label: "Academic Unit and UH Stakeholders", y: 14 }
                        ]
                    }]
              },
               
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
                this.chart = new CanvasJS.Chart("chartContainer", this.chartOptions);
                this.chart.render();
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
                stages[i]['submission_date'] = '';
                stages[i]['date_of_completion'] = '';
                stages[i]['duration'] = normalDurations[i];
            }	
           },

           clearCalulatedDurationForChart(){
            let normalDurations = [7, 14, 30, 30, 14, 90, 30, 90, 30, 60, 90, 30, 45, 14];
            for(let i = 0; i < this.chartOptions['data'][0]['dataPoints'].length; i++){
                this.chartOptions['data'][0]['dataPoints'][i]['y'] = normalDurations[i];
            }
           },

           adjustDateOnAWeekend(stage, dateData, i, flag){
                //console.log(`Adjustiing Date Data passed in ${JSON.stringify(dateData)}`);
                let alteredDate;
                let day = moment(dateData).format('dddd');
               // console.log(`This date day is on a :${day}`);
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
                            this.chartOptions['data'][0]['dataPoints'][i]['y'] = stage[i]['duration'];
                        } else {
                            // This is a regular date
                            alteredDate = moment(dateData, 'MM-DD-YYYY').add(0, 'days').format('l');
                            // set the duration 
                            stage[i]['duration'] = (parseInt(stage[i]['duration']) + 0);
                              // populate the chart  with updated durations 
                            this.chartOptions['data'][0]['dataPoints'][i]['y'] = stage[i]['duration'];
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
                            this.chartOptions['data'][0]['dataPoints'][i]['y'] = stage[i]['duration'];
                        }  else {
                        // This is a regular date
                            alteredDate = moment(dateData, 'MM-DD-YYYY').add(2, 'days').format('l');
                             // set the duration 
                            stage[i]['duration'] = (parseInt(stage[i]['duration']) + 2);
                             // populate the chart  with updated durations 
                            this.chartOptions['data'][0]['dataPoints'][i]['y'] = stage[i]['duration'];
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
                            this.chartOptions['data'][0]['dataPoints'][i]['y'] = stage[i]['duration'];    
                            }  else {
                                // This is a regular date
                                alteredDate = moment(dateData, 'MM-DD-YYYY').add(1, 'days').format('l');
                                // set the duration 
                                stage[i]['duration'] = (parseInt(stage[i]['duration']) + 1);
                                  // populate the chart  with updated durations 
                                this.chartOptions['data'][0]['dataPoints'][i]['y'] = stage[i]['duration'];
                            }
			    break;
                default:
                         // date is on a weekday return date
                        alteredDate = dateData;
                        // set the duration 
                        this.chartOptions['data'][0]['dataPoints'][i]['y'] = stage[i]['duration'];
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
                                console.log(`Chart Obj ${JSON.stringify(this.chartOptions['data'][0]['dataPoints'][i]['y'])}`);
                                stages[i]['submission_date'] = this.adjustDateOnAWeekend(stages, userInput, i, 1);	
                                let currentSubmissionDate = moment(stages[i]['submission_date'], 'MM-DD-YYYY').add(stages[i]['duration'], 'days').format('l');
                                let currentDateOfCompletion = stages[i]['date_of_completion'];
                                stages[i]['date_of_completion'] = this.adjustDateOnAWeekend(stages, currentSubmissionDate, i, 2);
                                console.log(`Adding ${stages[i]['duration']} days to ${stages[i]['submission_date']} to get ${currentDateOfCompletion}`);
                                this.chartOptions['data'][0]['dataPoints'][i]['y'] = stages[i]['duration'];
                        } 
                        else {
                            this.setNormalDates(stages, i);
                            if(this.isOnAHiatus(currentMonthIndex) && stages[i]['name'] === "UC/GPSC"){
                                this.hiatusDateAdjustment(chosenYear, stages, i);
                            }
                            if(stages[i]['name'] === "Board of Regents"){
                                this.dateAdjustmentForBoardOfRegentsMeeting(stages, chosenYear, i);
                            }
                            if(stages[i]['name'] === "THECB CAWS"){
                                this.adjustDateForCAWSQuarterly(stages, chosenYear, i);
                            }
                            if(stages[i]['name'] === "THECB Board Meeting"){
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
   