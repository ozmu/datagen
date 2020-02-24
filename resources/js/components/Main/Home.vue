<template>
    <!-- Dashboard content -->
    <div class="row">

        <div class="col-xl-12">
            <!-- Quick stats boxes -->
            <div class="row">
                <div class="col-lg-4">
                    <!-- Today's revenue -->
                    <div class="card bg-blue-400 home-widget">
                        <div class="card-body">
                            <div class="overlay" v-if="widgets.balance.loading">
                                <div class="lds-ring"><div></div><div></div><div></div><div></div></div>
                            </div>
                            <div class="d-flex">
                                <h3 class="font-weight-semibold mb-0">{{ widgets.balance.data.balance }}<b-icon icon="currency-try" custom-size="mdi-18px"></b-icon></h3>
                                <div class="list-icons ml-auto">
                                    <a class="list-icons-item" data-action="reload"></a>
                                </div>
                            </div>
                            <div>
                                Balance
                                <div class="font-size-sm opacity-75">{{ widgets.balance.data.notVerifiedBalance }}<b-icon icon="currency-try" custom-size="mdi-14px"></b-icon><span class="sub-text">not verified balance</span></div>
                            </div>
                        </div>
                    </div>
                    <!-- /today's revenue -->

                </div>

                <div class="col-lg-4">
                    <!-- Today's revenue -->
                    <div class="card bg-blue-400 home-widget">
                        <div class="card-body">
                            <div class="overlay" v-if="widgets.texts.loading">
                                <div class="lds-ring"><div></div><div></div><div></div><div></div></div>
                            </div>
                            <div class="d-flex">
                                <h3 class="font-weight-semibold mb-0">{{ widgets.texts.data.today }}</h3>
                                <div class="list-icons ml-auto">
                                    <a class="list-icons-item" data-action="reload"></a>
                                </div>
                            </div>
                            
                            <div>
                                Today marked texts
                                <div class="font-size-sm opacity-75">{{ widgets.texts.data.all }} <span class="sub-text">all marked texts</span></div>
                            </div>
                        </div>
                    </div>
                    <!-- /today's revenue -->

                </div>

                <div class="col-lg-4">
                    <!-- Members online -->
                    <div class="card bg-teal-400 home-widget">
                        <div class="card-body">
                            <div class="overlay" v-if="widgets.tags.loading">
                                <div class="lds-ring"><div></div><div></div><div></div><div></div></div>
                            </div>
                            <div class="d-flex">
                                <h3 class="font-weight-semibold mb-0">{{ widgets.tags.data.today }}</h3>
                                <span class="badge bg-teal-800 badge-pill align-self-center ml-auto">+53,6%</span>
                            </div>
                            
                            <div>
                                Today tags count
                                <div class="font-size-sm opacity-75">{{ widgets.tags.data.all }} <span class="sub-text">all tags count</span></div>
                            </div>
                        </div>
                    </div>
                    <!-- /members online -->

                </div>
            </div>
            <!-- /quick stats boxes -->

            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="overlay" v-if="charts.timeseries.loading">
                                <div class="lds-ring"><div></div><div></div><div></div><div></div></div>
                            </div>
                            <fusioncharts
                                type="timeseries"
                                width="100%"
                                height="600"
                                dataFormat="json"
                                :dataSource="dataSource.timeseries"
                                ></fusioncharts>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="overlay" v-if="charts.column.loading">
                                <div class="lds-ring"><div></div><div></div><div></div><div></div></div>
                            </div>
                            <fusioncharts
                            type="column2d"
                            width="100%"
                            height="400"
                            dataFormat="json"
                            :dataSource="dataSource.column"
                            ></fusioncharts>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="overlay" v-if="charts.pie.loading">
                                <div class="lds-ring"><div></div><div></div><div></div><div></div></div>
                            </div>
                            <fusioncharts
                            type="pie3d"
                            width="100%"
                            height="400"
                            dataFormat="json"
                            :dataSource="dataSource.pie"
                            ></fusioncharts>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /dashboard content -->
</template>

<script>
export default {
    data(){
        return {
            charts: {
                timeseries: {loading: true},
                column: {loading: true},
                pie: {loading: true}
            },
            dataSource: {
                timeseries: {
                    data: null,
                    caption: {
                        text: "Daily Gains"
                    },
                    subcaption: {
                        text: "Daily balances"
                    },
                    yAxis: [
                        {
                            plot: {
                                value: "Daily Gain",
                                type: "line"
                            },
                            format: {
                                prefix: "₺"
                            },
                            title: "Balance"
                        }
                    ]
                },

                column: {
                    chart: {
                        caption: "Daily marked text",
                        subCaption: "Daily marked text on a weekly basis",
                        xAxisName: "Day",
                        yAxisName: "Count",
                        theme: "fusion"
                    },
                    data: null
                },

                pie: {
                    chart: {
                        caption: "All time tags",
                        subCaption : "All marked tags",
                        showValues:"1",
                        showPercentInTooltip : "0",
                        enableMultiSlicing:"1",
                        theme: "fusion"
                    },
                    data: null
                }
            },
            widgets: {
                balance: {
                    loading: true,
                    data: {}
                },
                texts: {
                    loading: true,
                    data: {}
                },
                tags: {
                    loading: true,
                    data: {}
                }
            },
            msg: 'Hello World'
        }
    },

    mounted(){
        this.getWidgets("balance")
        this.getWidgets("texts")
        this.getWidgets("tags")
        /** TimeSeries chart */
        axios.get('/data/utils/charts?scope=gains').then(response => {
            const data = response.data;
            const schema = [
                {
                    "name": "Time",
                    "type": "date",
                    "format": "%d-%b-%y"
                },
                {
                    "name": "Daily Gain",
                    "type": "number"
                }
            ]
            const fusionTable = new FusionCharts.DataStore().createDataTable(
                data,
                schema
            )
            this.dataSource.timeseries.data = fusionTable
            this.charts.timeseries.loading = false
        })
        /** Column chart */
        axios.get('/data/utils/charts?scope=texts').then(response => {
            this.dataSource.column.data = response.data
            this.charts.column.loading = false
        })
        /** Pie chart */
        axios.get('/data/utils/charts?scope=tags&period=all').then(response => {
            var data = []
            for (let [key, value] of Object.entries(response.data)) {
                data.push({label: key.charAt(0) + key.slice(1).toLowerCase(), value: value.count, color: value.color})
            }
            this.dataSource.pie.data = data
            this.charts.pie.loading = false
        })
    },

    methods: {
        getWidgets(scope){
            axios.get('/data/utils/widgets?scope=' + scope).then(response => {
                this.widgets[scope].data = response.data
                this.widgets[scope].loading = false
            })
        }
    }
}
</script>

<style scoped>
.home-widget {
    color: #000000 !important;
}
span.sub-text {
    font-size: 10px;
}
</style>