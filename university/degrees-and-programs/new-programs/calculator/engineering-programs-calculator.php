
    

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
<style>
         /** Mobile ***/
         @media screen and (min-width: 320px) and (max-width: 820px){
            #amDiv{
                font-size: 1em;
            }
            .list-group-item h3{
                font-size: 0.9rem;
            }
            .custom-iframe{
              width:  100%;
              height: 2500px;
            }
            .pie-chart-container{
                min-wdith: 100%;
            }
            .pie-chart{
                width: 100%;
                height: 140px;
                font-size: 0.8rem!important;
            }
            .list-group-item{
                padding: .55rem 1.01rem !important;
            }
            iframe #custom-iframe{
                width: 100%;
                height: 2500px;
                min-height: 300vh;
            }
        }

         /** Tablet ***/
         @media screen and (min-width: 768px) and (max-width: 1140px){
            #amDiv{
                font-size: 1em;
            }
            .list-group-item h3{
                font-size: 0.93rem;
            }
            .pie-chart-container{
                min-width: 890px;
            }
            .pie-chart{
                width: 100%;
                height: 240px;
            }

            .list-group-item{
                padding: .55rem 1.01rem !important;
            }
            iframe #custom-iframe{
                width: 100%;
                height: 1500px;
                min-height: 100%;
            }
         }

          /** Desktop ***/
          @media screen and (min-width: 1200px) and (max-width: 2200px){
            .container-fluid {
                max-width: 85%;
                margin: 0 auto;
            }  
            .table td{
                padding: .43rem;
            }

            #amDiv{
                font-size: 0.8em;
            }
            .list-group-item h3{
                font-size: 1.0rem;
            }
            .pie-chart-container{
                min-width: 890px;
            }
            .pie-chart{
                width: 100%;
                height: 240px;
            }
            .list-group-item{
                padding: .55rem 1.01rem !important;
            }
            
          }
        </style>


<div id="programCalculator">
        <div class="container-fluid">
        <h2 id="top">{{header}}</h2>
        <br/>
        <h6 style="font-size: 0.8rem;">General Disclaimer</h6>
        <textarea readonly="readonly" rows="2" class="form-control">The Proposal Calculator is intended to be a general reference on the various stages of the new degree proposal process and the estimated amount of time required from submission to completion. A wide variety of factors may impact timelines.</textarea>
        <br/>
            <div class="row">
                    <div class="col-lg-12">
                        <form @submit.prevent="submitDate()">
                        <div class="row">
                            <div class="col-lg-8 col-xs-12">
                                <div class="input-group date">
                                     <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon2"><i class="fas fa-2x fa-calendar-alt"></i></span>
                                    </div>
                                    <date-picker v-validate="'required|date_format:MM/dd/yyyy'" name="dateFormatted" placeholder="MM/DD/YYYY" v-model="dateFormatted" :disabled="inputDisabled" :config="options" style="height: 49px;"></date-picker>
                                </div>
                            </div>   
                            <div class="col-lg-2 col-xs-12" v-if="!userSubmitted">
                            <a href="#bottom" v-smooth-scroll><b-button variant="danger" size="lg" @click="submitDate" style="width: 100%; margin: 0.2em;">Calculate</b-button></a>
                            </div>
                            <br/>
                            <div class="col-lg-2 col-xs-12">
                            <a href="#top" v-smooth-scroll><b-button variant="danger" size="lg" @click="clearDates" style="width: 100%; margin: 0.2em;">Clear</b-button></a>
                            </div>
                            <div class="col-lg-12 col-xs-12"><p style="color: red; margin-left: 20px;">{{message}}</p></div>
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
                                    <b-table responsive bordered striped hover :items="proposalStages" :fields="headers"></b-table>
                                </div>
                            </div>
                         </div>
                    </div>
                    <div class="col-lg-6 col-xs-12" id="bottom">
                    <!-- <h4 style="text-align: center;">New Bachelor's and Master's Programs with costs over $2M in first five years and Engineering</h4> -->
                         <div class="pie-chart-container"><div id="amDiv" class="pie-chart" ref="amDiv"></div></div>
                    </div>
                     <div class="col-lg-6 col-xs-12">
                            <ul class="list-group">
                            <li class="list-group-item"><h3>Total Duration in Days: &nbsp;<span v-if="totalDays != 0"><strong>{{ totalDays }}</strong></span></h3></li>
                                <li class="list-group-item"><h3>Total Duration in Weeks: &nbsp;<span v-if="totalWeeks != 0"><strong>{{ totalWeeks }}</strong></span></h3></li>
                                <li class="list-group-item"><h3>Total Duration in Months: &nbsp;<span v-if="totalMonths != 0"><strong>{{ totalMonths }}</strong></span></h3></li>
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
<script src="https://cdn.jsdelivr.net/npm/vue@2.6.10"></script>

<!-- Vee Validate -->
<script src="https://unpkg.com/vee-validate@2.2.15"></script>

<!-- Date-picker Vue-Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/vue-bootstrap-datetimepicker@5"></script>


<!-- Vue-Bootstrap Js -->
<script src="//unpkg.com/bootstrap-vue@latest/dist/bootstrap-vue.min.js"></script>

<script src="//polyfill.io/v3/polyfill.min.js?features=es2015%2CIntersectionObserver" crossorigin="anonymous"></script>



<!--AMCharts -->
<script src="//www.amcharts.com/lib/4/core.js"></script>
<script src="//www.amcharts.com/lib/4/charts.js"></script>
<script src="//www.amcharts.com/lib/4/themes/animated.js"></script>

<script src="https://unpkg.com/vue2-smooth-scroll"></script> 
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
<script src="../../../../_js/engineering-programs-calculator.js"></script>