var programCalculator = new Vue({
    el: '#programCalculator',
    created(){
        this.$validator.localize('en', dict);
    },
    mounted(){
         // this.showPieChart();
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
                {stage: "Academic Programs 1", submission_date: '...', duration: 14, date_of_completion: '...', notes: 'Preliminary Planning Review and Business Pro Forma review'},
                {stage: "Provost", submission_date: '...', duration: 14, date_of_completion: '...', notes: 'Proposal Review'},
                {stage: "Provosts' Council 1", submission_date: '...', duration: 30, date_of_completion: '...', notes: 'Meets monthly'},
                {stage: "Academic Unit 2", submission_date: '...', duration: 30, date_of_completion: '...', notes: 'Preparation of THECB documentation'},
                {stage: "Academic Programs 2", submission_date: '...', duration: 14, date_of_completion: '...', notes: 'THECB documentation review'},
                {stage: "UC/GPSC", submission_date: '...', duration: 90, date_of_completion: '...', notes: 'Meets monthly (September – May) - Summer Hiatus (June – August)'},
                {stage: "Provosts' Council 2", submission_date: '...', duration: 30, date_of_completion: '...', notes: 'Meets monthly'},
                {stage: "Board of Regents", submission_date: '...', duration: 90, date_of_completion: '...', notes: 'Meets quarterly (November, February, May, August)'},
                {stage: "Area Notification", submission_date: '...', duration: 30, date_of_completion: '...', notes: 'Notification of all institutions (50 miles)'},
                {stage: "THECB", submission_date: '...', duration: 60, date_of_completion: '...', notes: 'THECB review'},
                {stage: "THECB Committee Meeting (CAWS)", submission_date: '...', duration: 30, date_of_completion: '...', notes: 'Meets quarterly (September, December, March, June)'},
                {stage: "THECB Board Meeting", submission_date: '...', duration: 30, date_of_completion: '...', notes: 'Meets quarterly (October, January, April, July)'},
                {stage: "US Department of Education", submission_date: '...' , duration: 45, date_of_completion: '...', notes: 'Financial aid eligibility review'},
                {stage: "Academic Unit and UH Stakeholders", submission_date: '...', duration: 14, date_of_completion: '...', notes: 'PeopleSoft and Catalog implementation'},	
            ],
          },
    
  
          amChartData: [
                {stage: "Academic Programs 1",  duration: 14},
                {stage: "Provost",  duration: 14},
                {stage: "Provosts' Council 1", duration: 30},
                {stage: "Academic Unit 2", duration: 30},
                {stage: "Academic Programs 2",  duration: 14},
                {stage: "UC/GPSC", duration: 90 },
                {stage: "Provosts' Council 2",  duration: 30},
                {stage: "Board of Regents",  duration: 90},
                {stage: "Area Notification",  duration: 30},
                {stage: "THECB", duration: 60 },
                {stage: "THECB Committee Meeting (CAWS)", duration: 30 },
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

       createChart(){
                let amChart = am4core.create("amDiv", am4charts.PieChart);
                this.chart = amChart;
                am4core.useTheme(am4themes_animated);
                amChart.data = this.amChartData;
                 //  amChart.legend = new am4charts.Legend();
                let pieSeries = amChart.series.push(new am4charts.PieSeries());
                pieSeries.dataFields.value = "duration";
                pieSeries.dataFields.category = "stage";
                pieSeries.labels.template.text = "{category} -  {value.value} days";
                pieSeries.slices.template.tooltipText = "{category} -  {value.value} days";
                pieSeries.slices.template.stroke = am4core.color("#fff");
                pieSeries.slices.template.strokeWidth = 2;
                pieSeries.slices.template.strokeOpacity = 1;
                // This creates initial animation
                pieSeries.hiddenState.properties.opacity = 1;
                pieSeries.hiddenState.properties.endAngle = -90;
       },

        showPieChart(){
            if (this.chart) {
                this.chart.dispose();
                this.createChart();
            } else {
                this.createChart();
            }
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
                    // this.showPieChart();
                   }
                 }    
            } else{
                this.message = 'Please correct the errors';
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
         // this.showPieChart(); 
         this.displaySummary = false;
        },
       
       clearProposal({stages}){
        let normalDurations = [14, 14, 30, 30, 14, 90, 30, 90, 30, 60, 30, 30, 45, 14];
        for(let i = 0; i < stages.length; i++){
            stages[i]['submission_date'] = '...';
            stages[i]['date_of_completion'] = '...';
            stages[i]['duration'] = normalDurations[i];
        }	
       },

       clearCalulatedDurationForChart(){
        let normalDurations = [14, 14, 30, 30, 14, 90, 30, 90, 30, 60, 30, 30, 45, 14];
        for(let i = 0; i <  this.amChartData.length; i++){
            this.amChartData[i]['duration'] = normalDurations[i];
        }
       },

       adjustDateOnAWeekend(stage, dateData, i, flag){
            let alteredDate;
            let day = moment(dateData, 'MM-DD-YYYY').format('dddd');
           switch(day){
             case 'Friday':
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
        let previousStageDateOfcompletion = stage[i - 1]['date_of_completion'];
        stage[i]['submission_date'] =  previousStageDateOfcompletion;
        let currentStageSubmissionDate = this.adjustDateOnAWeekend(stage, stage[i]['submission_date'], i, 1);
        let currentDateOfCompletion =  moment(currentStageSubmissionDate, 'MM-DD-YYYY').add(stage[i]['duration'], 'days').format('l');
        stage[i]['date_of_completion'] = this.adjustDateOnAWeekend(stage, currentDateOfCompletion, i, 2);
       },


       

       hiatusDateAdjustment (yearUserChose, stage, i) {
        /**
        *   This function adjusts the proposal date calculation at the stage 'UC/GPSC'
        *   It also checks the previous stage's date of completion and tests if it after April 1st
        *   it takes as arguments the stage array, the date user entered, and the current iteration index
        *****/

         // Calculate normally 
          stage[i]['submission_date'] =  this.adjustDateOnAWeekend(stage, stage[i - 1]['date_of_completion'], i, 1);
          let currentDateOfCompletion  = moment(stage[i]['submission_date'], 'MM-DD-YYYY').add(stage[i]['duration'], 'days').format('l');
          stage[i]['date_of_completion']   = this.adjustDateOnAWeekend(stage, currentDateOfCompletion, i , 2);
      
       let previousStageDateOfCompletion  = this.adjustDateOnAWeekend(stage, stage[i - 1]['date_of_completion'], i, 2);
       let yearOfPrvDateOfCompletion = moment(previousStageDateOfCompletion, 'MM-DD-YYYY')._d.getFullYear();
       let year = parseInt(yearOfPrvDateOfCompletion) > parseInt(yearUserChose) ?  yearOfPrvDateOfCompletion.toString() : yearUserChose;
       let aprilDeadline = moment('6/1/'+year, 'MM-DD-YYYY').subtract(60, 'days').format('l');
        //let monthOfApril = moment('4/1/'+year, 'MM-DD-YYYY').format('l');
       let monthOfAug = moment('8/31/'+year, 'MM-DD-YYYY').format('l');
       let hiatusStart = moment('5/31/'+year, 'MM-DD-YYYY').format('l');
       console.log(`April Dealine ${aprilDeadline}`);
       console.log(`Hiatus End Deadline ${monthOfAug}`);
       console.log(`Previous Stage Date of Completion ${previousStageDateOfCompletion}`);
       console.log(`Year of Previous Stage Date of Completion ${yearOfPrvDateOfCompletion}`);
    if(moment(previousStageDateOfCompletion, 'MM-DD-YYYY').isBetween(hiatusStart, monthOfAug)){
        console.log('Date of Completion is "DURING" Hiatus');
        let firstofSept = moment('9/1/'+year, 'MM-DD-YYYY').format('l');
        stage[i]['submission_date'] = this.adjustDateOnAWeekend(stage, firstofSept, i, 1);
        stage[i]['date_of_completion'] = moment(stage[i]['submission_date'], 'MM-DD-YYYY').add(stage[i]['duration'], 'days').format('l');
        stage[i]['date_of_completion'] = this.adjustDateOnAWeekend(stage, stage[i]['date_of_completion'], i, 2)

       } else if (moment(previousStageDateOfCompletion, 'MM-DD-YYYY').isBetween(aprilDeadline, hiatusStart)){
        stage[i]['submission_date'] =  this.adjustDateOnAWeekend(stage, stage[i - 1]['date_of_completion'], i, 1);
        stage[i]['date_of_completion'] =  moment(stage[i]['submission_date'], 'MM-DD-YYYY').add(stage[i]['duration'], 'days').format('l');
        let duration = moment(stage[i]['date_of_completion']).diff(stage[i]['submission_date'], 'days');
        duration = duration  + stage[i]['duration'];
        stage[i]['duration'] = duration;
        stage[i]['date_of_completion'] = moment('8/1/'+year, 'MM-DD-YYYY').add(stage[i]['duration'], 'days').format('l');
       } else if(moment(stage[i]['date_of_completion'], 'MM-DD-YYYY').isBetween(hiatusStart, monthOfAug)){
        let duration = moment(stage[i]['date_of_completion']).diff(stage[i]['submission_date'], 'days');
        duration = duration  + stage[i]['duration'];
        stage[i]['duration'] = duration;
        stage[i]['date_of_completion'] = moment(stage[i]['submission_date'], 'MM-DD-YYYY').add(stage[i]['duration'], 'days').format('l');

       } else {
            // Calculate normally 
        stage[i]['submission_date'] =  this.adjustDateOnAWeekend(stage, stage[i - 1]['date_of_completion'], i, 1);
        let currentDateOfCompletion  = moment(stage[i]['submission_date'], 'MM-DD-YYYY').add(stage[i]['duration'], 'days').format('l');
        stage[i]['date_of_completion']   = this.adjustDateOnAWeekend(stage, currentDateOfCompletion, i , 2);
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
            console.log(`Initial year Board of Regents${yearUserChose}`);
            let previousStageDateOfCompletion  = stage[i - 1]['date_of_completion'];
            // Get the full Year of the previous stage's date of completion
            let yearOfPrvDateOfCompletion = moment(previousStageDateOfCompletion)._d.getFullYear();
            // Get the month index of the previous stage's date of completion       
            let  monthIndex = moment(previousStageDateOfCompletion)._d.getMonth();
            let  month  =  moment(previousStageDateOfCompletion).format("MMM");
            // Check if the previous stage's date of completion is after year user entered if it is then return that year if its not then return the year the user entered
            //let year = moment(previousStageDateOfCompletion).isAfter(yearUserChose) ? yearOfPrvDateOfCompletion.toString() : yearUserChose;
            let year = parseInt(yearOfPrvDateOfCompletion) > parseInt(yearUserChose) ?  yearOfPrvDateOfCompletion.toString() : yearUserChose;
            let currentYear = year;
            let lastYear = (parseInt(year) - 1);
            let nextYear = (parseInt(year) + 1);
            if(moment(previousStageDateOfCompletion).isBetween('8/1/'+year, '10/31/'+year)){
                console.log(`Board meeting should be set to Nov`);
                stage[i]['submission_date'] = this.adjustDateOnAWeekend(stage, '11/1/'+year, i, 3);
                stage[i]['date_of_completion'] = moment(stage[i]['submission_date'], 'MM-DD-YYYY').add(stage[i]['duration'], 'days').format('l');
                stage[i]['date_of_completion'] = this.adjustDateOnAWeekend(stage, stage[i]['date_of_completion'], i, 2);
                     let novemberBoardMonth = this.adjustDateOnAWeekend(stage, '11/1/'+ year, i, 3);
                     let sixtyDaysBeforeNovember = moment(novemberBoardMonth, 'MM-DD-YYYY').subtract(60, 'days').format('l');
                      // Calculate sixty days before the first of the board meeting month and get that date
                      // Check if the previous stage's date of completion is before the date that is sixty days before the board meeting month
                     if(moment(previousStageDateOfCompletion, 'MM-DD-YYYY').isBefore(sixtyDaysBeforeNovember) || previousStageDateOfCompletion === sixtyDaysBeforeNovember){
                     stage[i]['submission_date']  = this.adjustDateOnAWeekend(stage, stage[i - 1]['date_of_completion'], i, 1);
                        let currentDateOfCompletion  =  moment(stage[i]['submission_date'], 'MM-DD-YYYY').add(stage[i]['duration'], 'days').format('l');
                        stage[i]['date_of_completion'] = this.adjustDateOnAWeekend(stage, currentDateOfCompletion, i, 2);
                     } else {
                        stage[i]['submission_date']  = this.adjustDateOnAWeekend(stage,'2/1/'+nextYear, i, 1);
                        let currentDateOfCompletion  = moment(stage[i]['submission_date'], 'MM-DD-YYYY').add(stage[i]['duration'], 'days').format('l');
                        stage[i]['date_of_completion'] = this.adjustDateOnAWeekend(stage, currentDateOfCompletion, i, 2);
                     }
            }
            if(moment(previousStageDateOfCompletion).isBetween('11/1/'+year, '1/31/'+nextYear)){
                console.log(`Board meeting should be set to Feb`);
                stage[i]['submission_date']  = this.adjustDateOnAWeekend(stage, '2/1/'+nextYear, i, 3);
                stage[i]['date_of_completion'] = moment(stage[i]['submission_date'], 'MM-DD-YYYY').add(stage[i]['duration'], 'days').format('l');
                stage[i]['date_of_completion'] = this.adjustDateOnAWeekend(stage, stage[i]['date_of_completion'], i, 2);
                
                        let febBoardMonth = this.adjustDateOnAWeekend(stage, '2/1/'+nextYear, i, 3);
                        let sixtyDaysBeforeFeb = moment(febBoardMonth, 'MM-DD-YYYY').subtract(60, 'days').format('l');
                         // Calculate sixty days before the first of the board meeting month and get that date
                         // Check if the previous stage's date of completion is before the date that is sixty days before the board meeting month
                        if(moment(previousStageDateOfCompletion, 'MM-DD-YYYY').isBefore(sixtyDaysBeforeFeb) || previousStageDateOfCompletion  === sixtyDaysBeforeFeb){
                            // If previous stage's date of completion IS PRIOR to that date that is 60days before board meeting 
                            // then calculate dates normally
                            stage[i]['submission_date']  = this.adjustDateOnAWeekend(stage, stage[i - 1]['date_of_completion'], i, 1);
                            let currentDateOfCompletion = moment(stage[i]['submission_date'], 'MM-DD-YYYY').add(stage[i]['duration'], 'days').format('l');
                            stage[i]['date_of_completion'] = this.adjustDateOnAWeekend(stage, currentDateOfCompletion, i, 2);
                        } else {
                            // If previous stage's date of completion IS NOT PRIOR to that date that is 60 days before board meeting 
                            // then jump to next board meeting month
                            stage[i]['submission_date']  = this.adjustDateOnAWeekend(stage, '5/1/'+nextYear, i, 1);
                            let currentDateOfCompletion = moment(stage[i]['submission_date'], 'MM-DD-YYYY').add(stage[i]['duration'], 'days').format('l');
                            stage[i]['date_of_completion']  =  this.adjustDateOnAWeekend(stage, currentDateOfCompletion, i, 2);
                        }
                    
            }
            if(moment(previousStageDateOfCompletion).isBetween('2/1/'+year, '4/31/'+year)){
                console.log(`Board meeting should be set to May`);
                stage[i]['submission_date']  = this.adjustDateOnAWeekend(stage, '5/1/'+year, i, 3);
                stage[i]['date_of_completion'] = moment(stage[i]['submission_date'], 'MM-DD-YYYY').add(stage[i]['duration'], 'days').format('l');
                stage[i]['date_of_completion'] = this.adjustDateOnAWeekend(stage, stage[i]['date_of_completion'], i, 2);
                
                      let mayBoardMonth = this.adjustDateOnAWeekend(stage, '5/1/'+ year, i, 3);
                       // Calculate sixty days before the first of the board meeting month and get that date
                       // Check if the previous stage's date of completion is before the date that is sixty days before the board meeting month
                      let sixtyDaysBeforeMay = moment(mayBoardMonth,'MM-DD-YYYY').subtract(60, 'days').format('l');
                      if(moment(previousStageDateOfCompletion, 'MM-DD-YYYY').isBefore(sixtyDaysBeforeMay) || previousStageDateOfCompletion  === sixtyDaysBeforeMay){
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
                
            }
            if(moment(previousStageDateOfCompletion).isBetween('5/1/'+year, '7/31/'+year)){
                console.log(`Board meeting should be set to Aug`);
                stage[i]['submission_date']  = this.adjustDateOnAWeekend(stage, '8/1/'+year, i, 3);
                 stage[i]['date_of_completion'] = moment(stage[i]['submission_date'], 'MM-DD-YYYY').add(stage[i]['duration'], 'days').format('l');
                stage[i]['date_of_completion'] = this.adjustDateOnAWeekend(stage, stage[i]['date_of_completion'], i, 2);
                
                    let augustBoardMonth = this.adjustDateOnAWeekend(stage, '8/1/'+ year, i, 3);
                     // Calculate sixty days before the first of the board meeting month and get that date
                     // Check if the previous stage's date of completion is before the date that is sixty days before the board meeting month
                    let sixtyDaysBeforeAugust = moment(augustBoardMonth, 'MM-DD-YYYY').subtract(60, 'days').format('l');
                     if(moment(previousStageDateOfCompletion, 'MM-DD-YYYY').isBefore(sixtyDaysBeforeAugust) || previousStageDateOfCompletion === sixtyDaysBeforeAugust){
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
                
            }

    },

    calculateDates({stages}, date){
            var chosenYear = moment(date, 'MM-DD-YYYY')._d.getFullYear();
            console.log(`Chosen Year ${chosenYear}`)
            let currentDateToday = moment().format('l');
            let userInput = moment(date, 'MM-DD-YYYY').format('l');
            let isBeforeToday = moment(userInput, 'MM-DD-YYYY').isBefore(new Date(currentDateToday).toISOString());
            if(userInput){
                for(let i = 0; i < stages.length; i++){
                    if(i < 1){
                            // This will be user's input
                            stages[i]['submission_date'] = this.adjustDateOnAWeekend(stages, userInput, i, 1);	
                            let currentSubmissionDate = moment(stages[i]['submission_date'], 'MM-DD-YYYY').add(stages[i]['duration'], 'days').format('l');
                            let currentDateOfCompletion = stages[i]['date_of_completion'];
                            stages[i]['date_of_completion'] = this.adjustDateOnAWeekend(stages, currentSubmissionDate, i, 2);
                            this.amChartData[i]['duration'] = stages[i]['duration'];
                    } 
                    else {
                        this.setNormalDates(stages, i);
                        if(stages[i]['stage'] === "UC/GPSC"){
                            this.hiatusDateAdjustment(chosenYear, stages, i);
                        }
                        if(stages[i]['stage'] === "Board of Regents"){
                            this.dateAdjustmentForBoardOfRegentsMeeting(stages, chosenYear, i);
                        }
                        if(stages[i]['stage'] === "THECB Committee Meeting (CAWS)"){
                            this.adjustDateForCAWSQuarterly(stages, chosenYear, i);
                        }
                        if(stages[i]['stage'] === "THECB Board Meeting"){
                            this.adjustDateForCAWSBoardMeetingQuarterly(stages, chosenYear, i);
                        }
                    }
                }
               return true; // function is done executing return true
            }   else {
                  // User entered Date in the past
                    this.message = "Please enter a valid date!";
                    return false;
            }
     },


     adjustDateForCAWSQuarterly(stage, yearUserChose, i){
        let previousStageDateOfCompletion  = stage[i - 1]['date_of_completion'];
        let yearOfPrvDateOfCompletion = moment(previousStageDateOfCompletion)._d.getFullYear();
        let year = parseInt(yearOfPrvDateOfCompletion) > parseInt(yearUserChose) ?  yearOfPrvDateOfCompletion.toString() : yearUserChose;
        let currentYear = year;
        let lastYear = (parseInt(year) - 1);
        let nextYear = (parseInt(year) + 1);
        let  month  =  moment(previousStageDateOfCompletion).format("MMM");
        	//Check of previous date of completion is between June an Aug, close to Sept
			   if(moment(previousStageDateOfCompletion).isBetween('6/1/'+year, '8/31/'+year)){
                console.log(`Board meeting should be set to Sept`);
                stage[i]['submission_date'] = this.adjustDateOnAWeekend(stage, '9/1/'+year, i, 3);
                stage[i]['date_of_completion'] = moment(stage[i]['submission_date'], 'MM-DD-YYYY').add(stage[i]['duration'], 'days').format('l');
                stage[i]['date_of_completion'] = this.adjustDateOnAWeekend(stage, stage[i]['date_of_completion'], i, 2);
                     let septBoardMonth = this.adjustDateOnAWeekend(stage, '9/1/'+year, i, 3);
                     let sixtyDaysBeforeSept = moment(septBoardMonth, 'MM-DD-YYYY').subtract(60, 'days').format('l');
                      // Calculate sixty days before the first of the board meeting month and get that date
                      // Check if the previous stage's date of completion is before the date that is sixty days before the board meeting month
                     if(moment(previousStageDateOfCompletion, 'MM-DD-YYYY').isBefore(sixtyDaysBeforeSept) || previousStageDateOfCompletion === sixtyDaysBeforeSept){
                     stage[i]['submission_date']  = this.adjustDateOnAWeekend(stage, stage[i - 1]['date_of_completion'], i, 1);
                        let currentDateOfCompletion  =  moment(stage[i]['submission_date'], 'MM-DD-YYYY').add(stage[i]['duration'], 'days').format('l');
                        stage[i]['date_of_completion'] = this.adjustDateOnAWeekend(stage, currentDateOfCompletion, i, 2);
                     } else {
                        stage[i]['submission_date']  = this.adjustDateOnAWeekend(stage,'12/1/'+year, i, 1);
                        let currentDateOfCompletion  = moment(stage[i]['submission_date'], 'MM-DD-YYYY').add(stage[i]['duration'], 'days').format('l');
                        stage[i]['date_of_completion'] = this.adjustDateOnAWeekend(stage, currentDateOfCompletion, i, 2);
                     }
            }
            //Check of previous date of completion is between  Sept an Nov, close to Dec
            if(moment(previousStageDateOfCompletion).isBetween('9/1/'+year, '11/31/'+year)){
                console.log(`Board meeting should be set to Dec`);
                stage[i]['submission_date']  = this.adjustDateOnAWeekend(stage, '12/1/'+year, i, 3);
                stage[i]['date_of_completion'] = moment(stage[i]['submission_date'], 'MM-DD-YYYY').add(stage[i]['duration'], 'days').format('l');
                stage[i]['date_of_completion'] = this.adjustDateOnAWeekend(stage, stage[i]['date_of_completion'], i, 2);
                
                        let decBoardMonth = this.adjustDateOnAWeekend(stage, '12/1/'+year, i, 3);
                        let sixtyDaysBeforeDec = moment(decBoardMonth, 'MM-DD-YYYY').subtract(60, 'days').format('l');
                         // Calculate sixty days before the first of the board meeting month and get that date
                         // Check if the previous stage's date of completion is before the date that is sixty days before the board meeting month
                        if(moment(previousStageDateOfCompletion, 'MM-DD-YYYY').isBefore(sixtyDaysBeforeDec) || previousStageDateOfCompletion  === sixtyDaysBeforeDec){
                            // If previous stage's date of completion IS PRIOR to that date that is 60days before board meeting 
                            // then calculate dates normally
                            stage[i]['submission_date']  = this.adjustDateOnAWeekend(stage, stage[i - 1]['date_of_completion'], i, 1);
                            let currentDateOfCompletion = moment(stage[i]['submission_date'], 'MM-DD-YYYY').add(stage[i]['duration'], 'days').format('l');
                            stage[i]['date_of_completion'] = this.adjustDateOnAWeekend(stage, currentDateOfCompletion, i, 2);
                        } else {
                            // If previous stage's date of completion IS NOT PRIOR to that date that is 60 days before board meeting 
                            // then jump to next board meeting month
                            stage[i]['submission_date']  = this.adjustDateOnAWeekend(stage, '3/1/'+nextYear, i, 1);
                            let currentDateOfCompletion = moment(stage[i]['submission_date'], 'MM-DD-YYYY').add(stage[i]['duration'], 'days').format('l');
                            stage[i]['date_of_completion']  =  this.adjustDateOnAWeekend(stage, currentDateOfCompletion, i, 2);
                        }
                    
            }
            //Check of previous date of completion is between  Dec and Feb, close to March
            if(moment(previousStageDateOfCompletion).isBetween('12/1/'+year, '2/28/'+nextYear)){
                console.log(`Board meeting should be set to March`);
                stage[i]['submission_date']  = this.adjustDateOnAWeekend(stage, '3/1/'+nextYear, i, 3);
                stage[i]['date_of_completion'] = moment(stage[i]['submission_date'], 'MM-DD-YYYY').add(stage[i]['duration'], 'days').format('l');
                stage[i]['date_of_completion'] = this.adjustDateOnAWeekend(stage, stage[i]['date_of_completion'], i, 2);
                
                      let marchBoardMonth = this.adjustDateOnAWeekend(stage, '3/1/'+nextYear, i, 3);
                       // Calculate sixty days before the first of the board meeting month and get that date
                       // Check if the previous stage's date of completion is before the date that is sixty days before the board meeting month
                      let sixtyDaysBeforeMarch = moment(marchBoardMonth,'MM-DD-YYYY').subtract(60, 'days').format('l');
                      if(moment(previousStageDateOfCompletion, 'MM-DD-YYYY').isBefore(sixtyDaysBeforeMarch)){
                        // If previous stage's date of completion IS PRIOR to that date that is 60days before board meeting 
                        // then calculate dates normally
                        stage[i]['submission_date']  = stage[i - 1]['date_of_completion'];
                        stage[i]['date_of_completion'] = moment(stage[i]['submission_date'], 'MM-DD-YYYY').add(stage[i]['duration'], 'days').format('l');
                      } else {
                        // If previous stage's date of completion  IS NOT PRIOR to that date that is 60 days before board meeting 
                        // then jump to next board meeting month
                        stage[i]['submission_date']  = this.adjustDateOnAWeekend(stage,'6/1/'+nextYear, i, 1);
                        let currentDateOfCompletion  = moment(stage[i]['submission_date'], 'MM-DD-YYYY').add(stage[i]['duration'], 'days').format('l');
                        stage[i]['date_of_completion'] = this.adjustDateOnAWeekend(stage, currentDateOfCompletion, i, 2);
                    }
                
            }
            //Check of previous date of completion is between  March and May, close to June
            if(moment(previousStageDateOfCompletion).isBetween('3/1/'+year, '5/31/'+year)){
                console.log(`Board meeting should be set to Aug`);
                stage[i]['submission_date']  = this.adjustDateOnAWeekend(stage, '8/1/'+year, i, 3);
                 stage[i]['date_of_completion'] = moment(stage[i]['submission_date'], 'MM-DD-YYYY').add(stage[i]['duration'], 'days').format('l');
                stage[i]['date_of_completion'] = this.adjustDateOnAWeekend(stage, stage[i]['date_of_completion'], i, 2);
                
                    let juneBoardMonth = this.adjustDateOnAWeekend(stage, '6/1/'+year, i, 3);
                     // Calculate sixty days before the first of the board meeting month and get that date
                     // Check if the previous stage's date of completion is before the date that is sixty days before the board meeting month
                    let sixtyDaysBeforeJune = moment(juneBoardMonth, 'MM-DD-YYYY').subtract(60, 'days').format('l');
                     if(moment(previousStageDateOfCompletion, 'MM-DD-YYYY').isBefore(sixtyDaysBeforeJune) || previousStageDateOfCompletion === sixtyDaysBeforeJune){
                         // If previous stage's date of completion IS PRIOR to that date that is 60 days before board meeting 
                            // then calculate dates normally
                            stage[i]['submission_date']  = this.adjustDateOnAWeekend(stage, stage[i - 1]['date_of_completion'], i, 1);
                            let currentDateOfCompletion  = moment(stage[i]['submission_date'], 'MM-DD-YYYY').add(stage[i]['duration'], 'days').format('l');
                            stage[i]['date_of_completion'] =  this.adjustDateOnAWeekend(stage, currentDateOfCompletion, i, 2);
                     } else {
                         // If previous stage's date of completion  IS NOT PRIOR to that date that is 60 days before board meeting 
                        // then jump to next board meeting month
                        stage[i]['submission_date']  = this.adjustDateOnAWeekend(stage, '9/1/'+year,i, 3);
                        let currentDateOfCompletion  = moment(stage[i]['submission_date'], 'MM-DD-YYYY').add(stage[i]['duration'], 'days').format('l');
                        stage[i]['date_of_completion'] = this.adjustDateOnAWeekend(stage, currentDateOfCompletion, i, 2);
                    }
                
            }
     },

     adjustDateForCAWSBoardMeetingQuarterly(stage, yearUserChose, i){
        let previousStageDateOfCompletion  = stage[i - 1]['date_of_completion'];
        let yearOfPrvDateOfCompletion = moment(previousStageDateOfCompletion)._d.getFullYear();
        let year = parseInt(yearOfPrvDateOfCompletion) > parseInt(yearUserChose) ?  yearOfPrvDateOfCompletion.toString() : yearUserChose;
        let currentYear = year;
        let lastYear = (parseInt(year) - 1);
        let nextYear = (parseInt(year) + 1);
        let  month  =  moment(previousStageDateOfCompletion).format("MMM");
        
        if(moment(previousStageDateOfCompletion).isBetween('7/1/'+year, '9/31/'+year)){
            console.log(`Board meeting should be set to October`);
            stage[i]['submission_date'] = this.adjustDateOnAWeekend(stage, '10/1/'+year, i, 3);
            stage[i]['date_of_completion'] = moment(stage[i]['submission_date'], 'MM-DD-YYYY').add(stage[i]['duration'], 'days').format('l');
            stage[i]['date_of_completion'] = this.adjustDateOnAWeekend(stage, stage[i]['date_of_completion'], i, 2);
                 let octBoardMonth = this.adjustDateOnAWeekend(stage, '10/1/'+ year, i, 3);
                 let sixtyDaysBeforeOctober = moment(octBoardMonth, 'MM-DD-YYYY').subtract(60, 'days').format('l');
                  // Calculate sixty days before the first of the board meeting month and get that date
                  // Check if the previous stage's date of completion is before the date that is sixty days before the board meeting month
                 if(moment(previousStageDateOfCompletion, 'MM-DD-YYYY').isBefore(sixtyDaysBeforeOctober) || previousStageDateOfCompletion === sixtyDaysBeforeOctober){
                 stage[i]['submission_date']  = this.adjustDateOnAWeekend(stage, stage[i - 1]['date_of_completion'], i, 1);
                    let currentDateOfCompletion  =  moment(stage[i]['submission_date'], 'MM-DD-YYYY').add(stage[i]['duration'], 'days').format('l');
                    stage[i]['date_of_completion'] = this.adjustDateOnAWeekend(stage, currentDateOfCompletion, i, 2);
                 } else {
                    stage[i]['submission_date']  = this.adjustDateOnAWeekend(stage,'1/1/'+ (parseInt(year) + 1), i, 1);
                    let currentDateOfCompletion  = moment(stage[i]['submission_date'], 'MM-DD-YYYY').add(stage[i]['duration'], 'days').format('l');
                    stage[i]['date_of_completion'] = this.adjustDateOnAWeekend(stage, currentDateOfCompletion, i, 2);
                 }
        }
        if(moment(previousStageDateOfCompletion).isBetween('10/1/'+year, '12/31/'+year)){
            console.log(`Board meeting should be set to Jan`);
            stage[i]['submission_date']  = this.adjustDateOnAWeekend(stage, '1/1/'+(year + 1), i, 3);
            stage[i]['date_of_completion'] = moment(stage[i]['submission_date'], 'MM-DD-YYYY').add(stage[i]['duration'], 'days').format('l');
            stage[i]['date_of_completion'] = this.adjustDateOnAWeekend(stage, stage[i]['date_of_completion'], i, 2);
                    let janBoardMonth = this.adjustDateOnAWeekend(stage, '1/1/'+(year+1), i, 3);
                    let sixtyDaysBeforeJan = moment(janBoardMonth, 'MM-DD-YYYY').subtract(60, 'days').format('l');
                     // Calculate sixty days before the first of the board meeting month and get that date
                     // Check if the previous stage's date of completion is before the date that is sixty days before the board meeting month
                    if(moment(previousStageDateOfCompletion, 'MM-DD-YYYY').isBefore(sixtyDaysBeforeJan) || previousStageDateOfCompletion  === sixtyDaysBeforeJan){
                        // If previous stage's date of completion IS PRIOR to that date that is 60days before board meeting 
                        // then calculate dates normally
                        stage[i]['submission_date']  = this.adjustDateOnAWeekend(stage, stage[i - 1]['date_of_completion'], i, 1);
                        let currentDateOfCompletion = moment(stage[i]['submission_date'], 'MM-DD-YYYY').add(stage[i]['duration'], 'days').format('l');
                        stage[i]['date_of_completion'] = this.adjustDateOnAWeekend(stage, currentDateOfCompletion, i, 2);
                    } else {
                        // If previous stage's date of completion IS NOT PRIOR to that date that is 60 days before board meeting 
                        // then jump to next board meeting month
                        stage[i]['submission_date']  = this.adjustDateOnAWeekend(stage, '4/1/'+ year, i, 1);
                        let currentDateOfCompletion = moment(stage[i]['submission_date'], 'MM-DD-YYYY').add(stage[i]['duration'], 'days').format('l');
                        stage[i]['date_of_completion']  =  this.adjustDateOnAWeekend(stage, currentDateOfCompletion, i, 2);
                    }
                
        }
        if(moment(previousStageDateOfCompletion).isBetween('1/1/'+year, '3/31/'+year)){
            console.log(`Board meeting should be set to April`);
            stage[i]['submission_date']  = this.adjustDateOnAWeekend(stage, '4/1/'+year, i, 3);
            stage[i]['date_of_completion'] = moment(stage[i]['submission_date'], 'MM-DD-YYYY').add(stage[i]['duration'], 'days').format('l');
            stage[i]['date_of_completion'] = this.adjustDateOnAWeekend(stage, stage[i]['date_of_completion'], i, 2);
            
                  let aprilBoardMonth = this.adjustDateOnAWeekend(stage, '4/1/'+ year, i, 3);
                   // Calculate sixty days before the first of the board meeting month and get that date
                   // Check if the previous stage's date of completion is before the date that is sixty days before the board meeting month
                  let sixtyDaysBeforeApril = moment(aprilBoardMonth,'MM-DD-YYYY').subtract(60, 'days').format('l');
                  if(moment(previousStageDateOfCompletion, 'MM-DD-YYYY').isBefore(sixtyDaysBeforeApril)){
                      // If previous stage's date of completion IS PRIOR to that date that is 60days before board meeting 
                        // then calculate dates normally
                    stage[i]['submission_date']  = stage[i - 1]['date_of_completion'];
                    stage[i]['date_of_completion'] = moment(stage[i]['submission_date'], 'MM-DD-YYYY').add(stage[i]['duration'], 'days').format('l');
                  } else {
                    // If previous stage's date of completion  IS NOT PRIOR to that date that is 60 days before board meeting 
                    // then jump to next board meeting month
                    stage[i]['submission_date']  = this.adjustDateOnAWeekend(stage,'7/1/'+ year, i, 1);
                    let currentDateOfCompletion  = moment(stage[i]['submission_date'], 'MM-DD-YYYY').add(stage[i]['duration'], 'days').format('l');
                    stage[i]['date_of_completion'] = this.adjustDateOnAWeekend(stage, currentDateOfCompletion, i, 2);
                }
            
        }
        if(moment(previousStageDateOfCompletion).isBetween('4/1/'+year, '6/31/'+year)){
            console.log(`Board meeting should be set to July`);
            stage[i]['submission_date']  = this.adjustDateOnAWeekend(stage, '7/1/'+year, i, 3);
             stage[i]['date_of_completion'] = moment(stage[i]['submission_date'], 'MM-DD-YYYY').add(stage[i]['duration'], 'days').format('l');
            stage[i]['date_of_completion'] = this.adjustDateOnAWeekend(stage, stage[i]['date_of_completion'], i, 2);
            
                let julyBoardMonth = this.adjustDateOnAWeekend(stage, '7/1/'+ year, i, 3);
                 // Calculate sixty days before the first of the board meeting month and get that date
                 // Check if the previous stage's date of completion is before the date that is sixty days before the board meeting month
                let sixtyDaysBeforeJuly = moment(julyBoardMonth, 'MM-DD-YYYY').subtract(60, 'days').format('l');
                 if(moment(previousStageDateOfCompletion, 'MM-DD-YYYY').isBefore(sixtyDaysBeforeJuly)){
                     // If previous stage's date of completion IS PRIOR to that date that is 60 days before board meeting 
                        // then calculate dates normally
                        stage[i]['submission_date']  = this.adjustDateOnAWeekend(stage, stage[i - 1]['date_of_completion'], i, 1);
                        let currentDateOfCompletion  = moment(stage[i]['submission_date'], 'MM-DD-YYYY').add(stage[i]['duration'], 'days').format('l');
                        stage[i]['date_of_completion'] =  this.adjustDateOnAWeekend(stage, currentDateOfCompletion, i, 2);
                 } else {
                     // If previous stage's date of completion  IS NOT PRIOR to that date that is 60 days before board meeting 
                    // then jump to next board meeting month
                    stage[i]['submission_date']  = this.adjustDateOnAWeekend(stage, '10/1/'+ year,i, 3);
                    let currentDateOfCompletion  = moment(stage[i]['submission_date'], 'MM-DD-YYYY').add(stage[i]['duration'], 'days').format('l');
                    stage[i]['date_of_completion'] = this.adjustDateOnAWeekend(stage, currentDateOfCompletion, i, 2);
                }
            
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