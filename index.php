<?php


?>

  <link href="https://cdn.jsdelivr.net/npm/@mdi/font@3.x/css/materialdesignicons.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/vuetify@2.x/dist/vuetify.min.css" rel="stylesheet">
  
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
                                label="Date"
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
                    <v-flex xs12 sm12 md2 pa-1>
                        <v-btn dark depressed color="red darken-4" style="width: 100%; height: 56px;"@click="submitDate">Calculate</v-btn>
                    </v-flex>
                    <v-flex xs12 sm12 md2 pa-1>
                        <v-btn dark depressed color="red darken-4" style="width: 100%; height: 56px;" @click="clearDates">Clear</v-btn>
                    </v-flex>
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
                    ></v-data-table>
                </v-flex>
        </v-content>
        <br/>
        <br/>
        <v-content>
      
        <v-flex xs12 sm12 md12>
                     <div id="chartContainer" style="height: 360px; width: 100%;"></div>
                </v-flex>
        </v-content>
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
                            { label: "Academic Programs 2", y: 95 },
                            { label: "UC/GPSC", y: 68 },
                            { label: "Provosts' Council 2", y: 28 },
                            { label: "Board of Regents", y: 34 },
                            { label: "Area Notification", y: 34 },
                            { label: "THECB", y: 14 },
                            { label: "US Department of Education", y: 14 },
                            { label: "Academic Unit and UH Stakeholders", y: 14 }
                        ]
                    }]
              },
               
          }
        },
       methods: {
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
                this.calculateDates(this.proposal, this.dateFormatted);
                // this.clearDates();
              }
            },
            clearDates(){
             this.dateFormatted = '';
		     this.clearProposal(this.proposal);
            },
           
           clearProposal({stages}){
            let normalDurations = [7, 14, 30, 30, 14, 60, 30, 90, 30, 60, 45, 14];
            for(let i = 0; i < stages.length; i++){
                stages[i]['submission_date'] = '';
                stages[i]['date_of_completion'] = '';
                stages[i]['duration'] = normalDurations[i];
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
                        stage[i]['duration'] = (parseInt(stage[i]['duration']) + 0);
                        if(flag == 1){
                                alteredDate = moment(dateData).add(0, 'days').format('l');
                                stage[i]['submission_date'] = alteredDate;
                        }
                        if(flag == 2){
                            alteredDate = moment(dateData).add(0, 'days').format('l');
                            stage[i]['date_of_completion'] = alteredDate;
                        }
                        } else {
                            // This is a regular date
                            alteredDate = moment(dateData).add(0, 'days').format('l');
                            stage[i]['duration'] = (parseInt(stage[i]['duration']) + 0);
                        }
			    break;
			    case 'Saturday':
                  // console.log(`This is a : ${flag == 1 ? 'submission_date' : flag == 2 ? 'date_of_completion' : 'regular date'}`);
                    if(flag == 1 || flag == 2){
                            stage[i]['duration'] = (parseInt(stage[i]['duration']) + 2);
                        if(flag == 1){
                                alteredDate = moment(dateData).add(2, 'days').format('l');
                                stage[i]['submission_date'] = alteredDate;
                        }
                        if(flag == 2){
                                alteredDate = moment(dateData).add(2, 'days').format('l');
                                    stage[i]['date_of_completion'] = alteredDate;
                        }
                        }  else {
                        // This is a regular date
                            alteredDate = moment(dateData).add(2, 'days').format('l');
                        stage[i]['duration'] = (parseInt(stage[i]['duration']) + 2);
                        }
			    break;
			    case 'Sunday':
                       // console.log(`This is a : ${flag == 1 ? 'submission_date' : flag == 2 ? 'date_of_completion' : 'regular date'}`);
                        if(flag == 1){
                            stage[i]['duration'] = (parseInt(stage[i]['duration']) + 1);
                            
                            if(flag == 1){
                                alteredDate = moment(dateData).add(1, 'days').format('l');
                                stage[i]['submission_date'] = alteredDate;
                            }
                            if(flag == 2){
                                alteredDate = moment(dateData).add(1, 'days').format('l');
                                    stage[i]['date_of_completion'] = alteredDate;
                            }
                            }  else {
                                // This is a regular date
                                alteredDate = moment(dateData).add(1, 'days').format('l');
                                stage[i]['duration'] = (parseInt(stage[i]['duration']) + 1);
                            }
			    break;
                default:
                        alteredDate = dateData;
                    break;
		        } // End of Switch

                return alteredDate;
           },

           setNormalDates(stage, i){
           // console.log(`Object iterating ${JSON.stringify(stage, null, 2)}`);
            let previousStageDateOfcompletion = stage[i - 1]['date_of_completion'];
            stage[i]['submission_date'] =  previousStageDateOfcompletion;
            let currentStageSubmissionDate = this.adjustDateOnAWeekend(stage, stage[i]['submission_date'], i, 1);
            let currentDateOfCompletion =  moment(currentStageSubmissionDate).add(stage[i]['duration'], 'days').format('l');
            stage[i]['date_of_completion'] = this.adjustDateOnAWeekend(stage, currentDateOfCompletion, i, 2);
           
           },


           isOnAHiatus(currentMonthIndex){
            return currentMonthIndex  > 4  && currentMonthIndex < 8 ? true: false;
           },

           hiatusDateAdjustment (yearUserChose, stage, i) {
                let marchDealine = this.adjustDateOnAWeekend(stage, '3/1/' + yearUserChose, i, 3);
                let previousStageDateOfCompletion  = this.adjustDateOnAWeekend(stage, stage[i - 1]['date_of_completion'], i, 2);
                if(moment(previousStageDateOfCompletion).isAfter(marchDealine)){
                    // console.log(`The entered date 'IS' after the March 31st dealine`);
                    stage[i]['submission_date'] = this.adjustDateOnAWeekend(stage, '9/1/'+ yearUserChose, i, 1);
                    let currentStageSubmissionDate = moment(stage[i]['submission_date']).add(stage[i]['duration'], 'days').format('l');
                    stage[i]['date_of_completion'] = this.adjustDateOnAWeekend(stage, currentStageSubmissionDate, i, 2);
            
                } else {
                    //  console.log(`The entered date 'IS NOT' after the March 31st dealine`);
                    stage[i]['submission_date'] =  this.adjustDateOnAWeekend(stage, stage[i - 1]['date_of_completion'], i, 1);
                    let currentDateOfCompletion  = moment(stage[i]['submission_date']).add(stage[i]['duration'], 'days').format('l');
                    stage[i]['date_of_completion']   = this.adjustDateOnAWeekend(stage, currentDateOfCompletion, i , 2);
                }
		
            },


            dateAdjustmentForBoardOfRegentsMeeting(stage, yearUserChose, i){
  		/*  console.log(`Stage Array Passed In ${JSON.stringify(stage, null, 2)}`);
  		       console.log(`Object at index ${i} ${JSON.stringify(stage[i - 1])}`);
  		       console.log(`Date of Completion at index ${i} ${JSON.stringify(stage[i - 1]['date_of_completion'])}`);
  		 console.log(`Index ${i}`); */
			let previousStageDateOfCompletion  = stage[i - 1]['date_of_completion'];
   /*     console.log('previousStageDateOfCompletion: ' + previousStageDateOfCompletion); */
			let  monthIndex = moment(previousStageDateOfCompletion)._d.getMonth();
      /*  console.log('monthIndex ' + monthIndex); */
			let  month  =  moment(previousStageDateOfCompletion).format("MMM");
      /* console.log('Month ' + month); */
			switch(month){
				case 'Feb':
				let febBoardMonth = this.adjustDateOnAWeekend(stage, '2/1/'+ yearUserChose, i, 3);
				let sixtyDaysBeforeFeb = moment(febBoardMonth).subtract(60, 'days').format('l');
				if(moment(previousStageDateOfCompletion).isBefore(sixtyDaysBeforeFeb)){
                    stage[i]['submission_date']  = this.adjustDateOnAWeekend(stage, stage[i - 1]['date_of_completion'], i, 1);
                    let currentDateOfCompletion = moment(stage[i]['submission_date']).add(stage[i]['duration'], 'days').format('l');
                    stage[i]['date_of_completion'] = this.adjustDateOnAWeekend(stage, currentDateOfCompletion, i, 2);
                } else {
                    stage[i]['submission_date']  = this.adjustDateOnAWeekend(stage, '5/1/'+ yearUserChose, i, 1);
                    let currentDateOfCompletion = moment(stage[i]['submission_date']).add(stage[i]['duration'], 'days').format('l');
                    stage[i]['date_of_completion']  =  this.adjustDateOnAWeekend(stage, currentDateOfCompletion, i, 2);
				}
				break;
				case 'May':
				let mayBoardMonth = this.adjustDateOnAWeekend(stage, '5/1/'+ yearUserChose, i, 3);
				let sixtyDaysBeforeMay = moment(mayBoardMonth).subtract(60, 'days').format('l');
                if(moment(previousStageDateOfCompletion).isBefore(sixtyDaysBeforeMay)){
                 stage[i]['submission_date']  = stage[i - 1]['date_of_completion'];
				 stage[i]['date_of_completion'] = moment(stage[i]['submission_date']).add(stage[i]['duration'], 'days').format('l');
             	} else {
                    stage[i]['submission_date']  = this.adjustDateOnAWeekend(stage,'8/1/'+ yearUserChose, i, 1);
                    let currentDateOfCompletion  = moment(stage[i]['submission_date']).add(stage[i]['duration'], 'days').format('l');
                    stage[i]['date_of_completion'] = this.adjustDateOnAWeekend(stage, currentDateOfCompletion, i, 2);
				}
				break;
				case 'Aug':
				let augustBoardMonth = this.adjustDateOnAWeekend(stage, '8/1/'+ yearUserChose, i, 3);
				let sixtyDaysBeforeAugust = moment(augustBoardMonth).subtract(60, 'days').format('l');
                if(moment(previousStageDateOfCompletion).isBefore(sixtyDaysBeforeAugust)){
                    stage[i]['submission_date']  = this.adjustDateOnAWeekend(stage, stage[i - 1]['date_of_completion'], i, 1);
                    let currentDateOfCompletion  = moment(stage[i]['submission_date']).add(stage[i]['duration'], 'days').format('l');
                    stage[i]['date_of_completion'] =  this.adjustDateOnAWeekend(stage, currentDateOfCompletion, i, 2);
                } else {
                    stage[i]['submission_date']  = this.adjustDateOnAWeekend(stage, '11/1/'+ yearUserChose,i, 3);
                    let currentDateOfCompletion  = moment(stage[i]['submission_date']).add(stage[i]['duration'], 'days').format('l');
                    stage[i]['date_of_completion'] = this.adjustDateOnAWeekend(stage, currentDateOfCompletion, i, 2);
                }
				break;
				case 'Nov':
				let novemeberBoardMonth = this.adjustDateOnAWeekend(stage, '11/1/'+ yearUserChose, i, 3);
				let sixtyDaysBeforeNovemeber = moment(novemeberBoardMonth).subtract(60, 'days').format('l');
                if(moment(previousStageDateOfCompletion).isBefore(sixtyDaysBeforeNovemeber)){
                    stage[i]['submission_date']  = this.adjustDateOnAWeekend(stage, stage[i - 1]['date_of_completion'], i, 1);
                    let currentDateOfCompletion  =  moment(stage[i]['submission_date']).add(stage[i]['duration'], 'days').format('l');
                    stage[i]['date_of_completion'] = this.adjustDateOnAWeekend(stage, currentDateOfCompletion, i, 2);
                } else {
                    stage[i]['submission_date']  = this.adjustDateOnAWeekend(stage,'2/1/'+ (parseInt(yearUserChose) + 1), i, 1);
                    let currentDateOfCompletion  = moment(stage[i]['submission_date']).add(stage[i]['duration'], 'days').format('l');
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
                var chosenYear = moment(date)._d.getFullYear().toString();
                let currentDateToday = moment().format('l');
                let userInput = moment(date).format('l');
                console.log(`Date User selected ${moment(date).format('l')}`);
                if(!moment(userInput).isBefore(currentDateToday)){
                    for(let i = 0; i < stages.length; i++){
                        if(i < 1){
                                // This will be user's input
                                console.log(`Chart Obj ${JSON.stringify(this.chartOptions['data'][0]['dataPoints'][i]['y'])}`);
                            let seventDaysFromNow = moment(userInput).add(stages[i]['duration'], 'days').format('l');
                            this.chartOptions['data'][0]['dataPoints'][i]['y'] = seventDaysFromNow;
                                stages[i]['submission_date'] = this.adjustDateOnAWeekend(stages, userInput, i, 1);	
                                stages[i]['date_of_completion'] = this.adjustDateOnAWeekend(stages, seventDaysFromNow, i, 2);
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
                /*  console.log(`Proposal 2: Poulated ${JSON.stringify(proposalTwo, null, 2)}`);
                console.log(`Proposal 3: Poulated ${JSON.stringify(proposalThree, null, 2)}`);  */
                }   else {
                      // User entered Date in the past
                        console.log('You entered a date in the past!');
                        return;
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
   