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
                        <p style="color: red;">{{ message }}</p>
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
            <v-flex xs12 sm6 md6 style="width: 100%;">
                        <div id="chartContainer" style="height: 360px; width: 100%;"></div>
            </v-flex>
            <v-flex xs12 sm6 md6>
                        <v-card>
                            <v-list-item>
                                <v-list-item-content>
                                    <v-list-item-title><h2>Total Duration in Days: &nbsp;{{ totalDays }}</h2></v-list-item-title>
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
                    {name: "UC/GPSC", submission_date: 'n/a', duration: 60, date_of_completion: 'n/a', notes: 'Monthly - Summer Hiatus June - Aug'},
                    {name: "Provosts' Council 2", submission_date: 'n/a', duration: 30, date_of_completion: 'n/a', notes: 'Monthly'},
                    {name: "Board of Regents", submission_date: 'n/a', duration: 90, date_of_completion: 'n/a', notes: 'Quarterly'},
                    {name: "Area Notification", submission_date: 'n/a', duration: 30, date_of_completion: 'n/a', notes: 'Notification of all institutions (50 miles)'},
                    {name: "THECB", submission_date: 'n/a', duration: 60, date_of_completion: 'n/a', notes: 'THECB Staff Review'},
                    {name: "US Department of Education", submission_date: 'n/a' , duration: 45, date_of_completion: 'n/a', notes: 'Financial Aid Eligibility'},
                    {name: "Academic Unit and UH Stakeholders", submission_date: 'n/a', duration: 14, date_of_completion: 'n/a', notes: 'PeopleSoft implementation, Catalog implementation, Application software updated, program available to applicants)'},	
                ],
              },
              chart : null,
              
              chartOptions: {
                    animationEnabled: true,
                    title: {
                    text: "New Bachelor's and Master's Programs with costs under $2M in first five years",
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
                            { label: "UC/GPSC", y: 60 },
                            { label: "Provosts' Council 2", y: 30 },
                            { label: "Board of Regents", y: 90 },
                            { label: "Area Notification", y: 30 },
                            { label: "THECB", y: 60 },
                            { label: "US Department of Education", y: 45 },
                            { label: "Academic Unit and UH Stakeholders", y: 14 }
                        ]
                    }]
              },
               
              totalDays: 0,
              totalMonths: 0,
              totalYears: 0, 
          }
        },
       methods: {
           clearTotals(){
            this.totalDays = 0;
            this.totalMonths = 0;
            this.totalYears = 0;
           },
           calculateTotals(){
            let submissionDate = moment(this.proposal.stages[0].submission_date);
            console.log(`Submission Date: ${JSON.stringify(submissionDate)}`);
            let proposalDate = moment(this.proposal.stages[this.proposal.stages.length - 1].date_of_completion);
            console.log(`Completion Date: ${JSON.stringify(proposalDate)}`);
            this.totalDays = proposalDate.diff(submissionDate, 'days');
            console.log(`Days: ${JSON.stringify(this.totalDays)}`);
            this.totalMonths = proposalDate.diff(submissionDate, 'months');
            console.log(`Months: ${JSON.stringify(this.totalMonths)}`);
            this.totalYears = proposalDate.diff(submissionDate, 'years');
            console.log(`Years: ${JSON.stringify(this.totalYears)}`);
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
                let success = this.calculateDates(this.proposal, this.dateFormatted);
                if(success){
                    this.displaySummary = true;
                    this.calculateTotals();
                    this.showPieChart();
                }
               
              }
            },
            clearDates(){
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
            let normalDurations = [7, 14, 30, 30, 14, 60, 30, 90, 30, 60, 45, 14];
            for(let i = 0; i < stages.length; i++){
                stages[i]['submission_date'] = '';
                stages[i]['date_of_completion'] = '';
                stages[i]['duration'] = normalDurations[i];
            }	
           },

           clearCalulatedDurationForChart(){
            let normalDurations = [7, 14, 30, 30, 14, 60, 30, 90, 30, 60, 45, 14];  
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
                        if(flag == 1 || flag == 2){
                            if(flag == 1){
                                    alteredDate = moment(dateData, 'MM-DD-YYYY').add(0, 'days').format('l');
                                    stage[i]['submission_date'] = alteredDate;
                            }
                            if(flag == 2){
                                alteredDate = moment(dateData, 'MM-DD-YYYY').add(0, 'days').format('l');
                                stage[i]['date_of_completion'] = alteredDate;
                            }
                            stage[i]['duration'] = (parseInt(stage[i]['duration']) + 0);
                            this.chartOptions['data'][0]['dataPoints'][i]['y'] = stage[i]['duration'];
                        } else {
                            // This is a regular date
                            alteredDate = moment(dateData, 'MM-DD-YYYY').add(0, 'days').format('l');
                            stage[i]['duration'] = (parseInt(stage[i]['duration']) + 0);
                            this.chartOptions['data'][0]['dataPoints'][i]['y'] = stage[i]['duration'];
                        }
			    break;
			    case 'Saturday':
                  // console.log(`This is a : ${flag == 1 ? 'submission_date' : flag == 2 ? 'date_of_completion' : 'regular date'}`);
                    if(flag == 1 || flag == 2){
                            if(flag == 1){
                                    alteredDate = moment(dateData, 'MM-DD-YYYY').add(2, 'days').format('l');
                                    stage[i]['submission_date'] = alteredDate;
                            }
                            if(flag == 2){
                                    alteredDate = moment(dateData, 'MM-DD-YYYY').add(2, 'days').format('l');
                                        stage[i]['date_of_completion'] = alteredDate;
                            }
                        stage[i]['duration'] = (parseInt(stage[i]['duration']) + 2);
                        this.chartOptions['data'][0]['dataPoints'][i]['y'] = stage[i]['duration'];
                        }  else {
                        // This is a regular date
                            alteredDate = moment(dateData, 'MM-DD-YYYY').add(2, 'days').format('l');
                            stage[i]['duration'] = (parseInt(stage[i]['duration']) + 2);
                            this.chartOptions['data'][0]['dataPoints'][i]['y'] = stage[i]['duration'];
                        }
			    break;
			    case 'Sunday':
                       // console.log(`This is a : ${flag == 1 ? 'submission_date' : flag == 2 ? 'date_of_completion' : 'regular date'}`);
                        if(flag == 1){
                                if(flag == 1){
                                    alteredDate = moment(dateData, 'MM-DD-YYYY').add(1, 'days').format('l');
                                    stage[i]['submission_date'] = alteredDate;
                                }
                                if(flag == 2){
                                    alteredDate = moment(dateData, 'MM-DD-YYYY').add(1, 'days').format('l');
                                        stage[i]['date_of_completion'] = alteredDate;
                                }
                            stage[i]['duration'] = (parseInt(stage[i]['duration']) + 1);    
                            this.chartOptions['data'][0]['dataPoints'][i]['y'] = stage[i]['duration'];    
                            }  else {
                                // This is a regular date
                                alteredDate = moment(dateData, 'MM-DD-YYYY').add(1, 'days').format('l');
                                stage[i]['duration'] = (parseInt(stage[i]['duration']) + 1);
                                this.chartOptions['data'][0]['dataPoints'][i]['y'] = stage[i]['duration'];
                            }
			    break;
                default:
                        alteredDate = dateData;
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
            return currentMonthIndex  > 4  && currentMonthIndex < 8 ? true: false;
           },

           hiatusDateAdjustment (yearUserChose, stage, i) {
              
                let previousStageDateOfCompletion  = this.adjustDateOnAWeekend(stage, stage[i - 1]['date_of_completion'], i, 2);
                let yearOfPrvDateOfCompletion = moment(previousStageDateOfCompletion)._d.getFullYear();
                let year = moment(previousStageDateOfCompletion).isAfter(yearUserChose) ? yearOfPrvDateOfCompletion.toString() : yearUserChose;
                let marchDeadline = this.adjustDateOnAWeekend(stage, '3/1/' + year, i, 3);
                console.log(`Hiatus Year value is: ${year} Previous Year is ${yearOfPrvDateOfCompletion.toString()} and Year user chose is ${yearUserChose}`);
                if(moment(previousStageDateOfCompletion ,'MM-DD-YYYY').isAfter(marchDeadline)){
                    if(moment(previousStageDateOfCompletion, 'MM-DD-YYYY').isBetween('3/2/'+year, '5/31/'+year) || moment(previousStageDateOfCompletion, 'MM-DD-YYYY').isBetween('6/1/'+year, '7/31/'+year)){
                         // Meeting  jumps to Sep
                        stage[i]['submission_date'] = this.adjustDateOnAWeekend(stage, '9/1/'+ year, i, 1);
                        let currentStageSubmissionDate = moment(stage[i]['submission_date'], 'MM-DD-YYYY').add(stage[i]['duration'], 'days').format('l');
                        stage[i]['date_of_completion'] = this.adjustDateOnAWeekend(stage, currentStageSubmissionDate, i, 2);
                    }
                    if(moment(previousStageDateOfCompletion).isAfter('7/31/'+ year)){
                        // Calculate dates normally
                        stage[i]['submission_date'] =  this.adjustDateOnAWeekend(stage, stage[i - 1]['date_of_completion'], i, 1);
                        let currentDateOfCompletion  = moment(stage[i]['submission_date'], 'MM-DD-YYYY').add(stage[i]['duration'], 'days').format('l');
                        stage[i]['date_of_completion']   = this.adjustDateOnAWeekend(stage, currentDateOfCompletion, i , 2);
                    }
                }
		
            },


            dateAdjustmentForBoardOfRegentsMeeting(stage, yearUserChose, i){
  		/*  console.log(`Stage Array Passed In ${JSON.stringify(stage, null, 2)}`);
  		       console.log(`Object at index ${i} ${JSON.stringify(stage[i - 1])}`);
  		       console.log(`Date of Completion at index ${i} ${JSON.stringify(stage[i - 1]['date_of_completion'])}`);
  		 console.log(`Index ${i}`); */
            
			let previousStageDateOfCompletion  = stage[i - 1]['date_of_completion'];
            let yearOfPrvDateOfCompletion = moment(previousStageDateOfCompletion)._d.getFullYear();
            console.log(`Prv date of completion ${yearOfPrvDateOfCompletion.toString()}`);
   /*     console.log('previousStageDateOfCompletion: ' + previousStageDateOfCompletion); */
			let  monthIndex = moment(previousStageDateOfCompletion)._d.getMonth();
      /*  console.log('monthIndex ' + monthIndex); */
			let  month  =  moment(previousStageDateOfCompletion).format("MMM");
      /* console.log('Month ' + month); */
            let year = moment(previousStageDateOfCompletion).isAfter(yearUserChose) ? yearOfPrvDateOfCompletion.toString() : yearUserChose;
            console.log(`BoardOfRegentsMeeting Year value is: ${year} Previous Year is ${yearOfPrvDateOfCompletion.toString()} and Year user chose is ${yearUserChose}`);
			switch(month){
				case 'Feb':
				let febBoardMonth = this.adjustDateOnAWeekend(stage, '2/1/'+ year, i, 3);
				let sixtyDaysBeforeFeb = moment(febBoardMonth, 'MM-DD-YYYY').subtract(60, 'days').format('l');
				if(moment(previousStageDateOfCompletion, 'MM-DD-YYYY').isBefore(sixtyDaysBeforeFeb)){
                    stage[i]['submission_date']  = this.adjustDateOnAWeekend(stage, stage[i - 1]['date_of_completion'], i, 1);
                    let currentDateOfCompletion = moment(stage[i]['submission_date'], 'MM-DD-YYYY').add(stage[i]['duration'], 'days').format('l');
                    stage[i]['date_of_completion'] = this.adjustDateOnAWeekend(stage, currentDateOfCompletion, i, 2);
                } else {
                    stage[i]['submission_date']  = this.adjustDateOnAWeekend(stage, '5/1/'+ year, i, 1);
                    let currentDateOfCompletion = moment(stage[i]['submission_date'], 'MM-DD-YYYY').add(stage[i]['duration'], 'days').format('l');
                    stage[i]['date_of_completion']  =  this.adjustDateOnAWeekend(stage, currentDateOfCompletion, i, 2);
				}
				break;
				case 'May':
				let mayBoardMonth = this.adjustDateOnAWeekend(stage, '5/1/'+ year, i, 3);
				let sixtyDaysBeforeMay = moment(mayBoardMonth,'MM-DD-YYYY').subtract(60, 'days').format('l');
                if(moment(previousStageDateOfCompletion, 'MM-DD-YYYY').isBefore(sixtyDaysBeforeMay)){
                 stage[i]['submission_date']  = stage[i - 1]['date_of_completion'];
				 stage[i]['date_of_completion'] = moment(stage[i]['submission_date'], 'MM-DD-YYYY').add(stage[i]['duration'], 'days').format('l');
             	} else {
                    stage[i]['submission_date']  = this.adjustDateOnAWeekend(stage,'8/1/'+ year, i, 1);
                    let currentDateOfCompletion  = moment(stage[i]['submission_date'], 'MM-DD-YYYY').add(stage[i]['duration'], 'days').format('l');
                    stage[i]['date_of_completion'] = this.adjustDateOnAWeekend(stage, currentDateOfCompletion, i, 2);
				}
				break;
				case 'Aug':
				let augustBoardMonth = this.adjustDateOnAWeekend(stage, '8/1/'+ year, i, 3);
				let sixtyDaysBeforeAugust = moment(augustBoardMonth, 'MM-DD-YYYY').subtract(60, 'days').format('l');
                if(moment(previousStageDateOfCompletion, 'MM-DD-YYYY').isBefore(sixtyDaysBeforeAugust)){
                    stage[i]['submission_date']  = this.adjustDateOnAWeekend(stage, stage[i - 1]['date_of_completion'], i, 1);
                    let currentDateOfCompletion  = moment(stage[i]['submission_date'], 'MM-DD-YYYY').add(stage[i]['duration'], 'days').format('l');
                    stage[i]['date_of_completion'] =  this.adjustDateOnAWeekend(stage, currentDateOfCompletion, i, 2);
                } else {
                    stage[i]['submission_date']  = this.adjustDateOnAWeekend(stage, '11/1/'+ year,i, 3);
                    let currentDateOfCompletion  = moment(stage[i]['submission_date'], 'MM-DD-YYYY').add(stage[i]['duration'], 'days').format('l');
                    stage[i]['date_of_completion'] = this.adjustDateOnAWeekend(stage, currentDateOfCompletion, i, 2);
                }
				break;
				case 'Nov':
				let novemeberBoardMonth = this.adjustDateOnAWeekend(stage, '11/1/'+ year, i, 3);
				let sixtyDaysBeforeNovemeber = moment(novemeberBoardMonth, 'MM-DD-YYYY').subtract(60, 'days').format('l');
                if(moment(previousStageDateOfCompletion, 'MM-DD-YYYY').isBefore(sixtyDaysBeforeNovemeber)){
                    stage[i]['submission_date']  = this.adjustDateOnAWeekend(stage, stage[i - 1]['date_of_completion'], i, 1);
                    let currentDateOfCompletion  =  moment(stage[i]['submission_date'], 'MM-DD-YYYY').add(stage[i]['duration'], 'days').format('l');
                    stage[i]['date_of_completion'] = this.adjustDateOnAWeekend(stage, currentDateOfCompletion, i, 2);
                } else {
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
                var currentMonthIndex  = moment()._d.getMonth();
                 console.log(`Proposal Arr passed in ${JSON.stringify(stages, null, 2)}`);
                var chosenYear = moment(date, 'YYYY')._d.getFullYear().toString();
                console.log(`Year User selected ${chosenYear}`);
                let currentDateToday = moment().format('l');
                let userInput = moment(date, 'MM-DD-YYYY').format('l');
                console.log(`Date User selected ${moment(date, 'MM-DD-YYYY').format('l')}`);
                let isBeforeToday = moment(new Date(userInput).toISOString(), 'MM-DD-YYYY').isBefore(new Date(currentDateToday).toISOString());
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
                        }
                    }
                    
                    console.log(`Proposal 1: Poulated ${JSON.stringify(this.proposal, null, 2)}`);
                   return true;
                /*  console.log(`Proposal 2: Poulated ${JSON.stringify(proposalTwo, null, 2)}`);
                console.log(`Proposal 3: Poulated ${JSON.stringify(proposalThree, null, 2)}`);  */
                }   else {
                      // User entered Date in the past
                        console.log('You entered a date in the past!');
                        this.message = "You entered a date in the past! Please Try Again...";
                        return false;
                }
         }
            
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
   