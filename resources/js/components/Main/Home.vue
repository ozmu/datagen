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

            <div class="card">
                <div class="col-md-12">
                    <fusioncharts
                        type="timeseries"
                        width="100%"
                        height="350"
                        dataFormat="json"
                        :dataSource="dataSource.timeseries"
                        ></fusioncharts>
                </div>
            </div>
            <div class="row">
                <div class="card col-md-6">
                    <fusioncharts
                    type="column2d"
                    width="100%"
                    height="350"
                    dataFormat="json"
                    :dataSource="dataSource.column"
                    ></fusioncharts>
                </div>
                <div class="card col-md-6">
                    <fusioncharts
                    type="pie3d"
                    width="100%"
                    height="350"
                    dataFormat="json"
                    :dataSource="dataSource.pie"
                    ></fusioncharts>
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
            dataSource: {
                column: {
                    "chart": {
                        "caption": "Countries With Most Oil Reserves [2017-18]",
                        "subCaption": "In MMbbl = One Million barrels",
                        "xAxisName": "Country",
                        "yAxisName": "Reserves (MMbbl)",
                        "numberSuffix": "K",
                        "theme": "fusion"
                    },
                    "data": [{
                        "label": "Venezuela",
                        "value": "290"
                    }, {
                        "label": "Saudi",
                        "value": "260"
                    }, {
                        "label": "Canada",
                        "value": "180"
                    }, {
                        "label": "Iran",
                        "value": "140"
                    }, {
                        "label": "Russia",
                        "value": "115"
                    }, {
                        "label": "UAE",
                        "value": "100"
                    }, {
                        "label": "US",
                        "value": "30"
                    }, {
                        "label": "China",
                        "value": "30"
                    }]
                },

                timeseries: {
                    data: null,
                    caption: {
                        text: "Sales Analysis"
                    },
                    subcaption: {
                        text: "Grocery"
                    },
                    yAxis: [
                        {
                            plot: {
                            value: "Grocery Sales Value",
                            type: "line"
                            },
                            format: {
                            prefix: "$"
                            },
                            title: "Sale Value"
                        }
                    ]
                },

                pie: {
                    chart: {
                        caption: "All time tags",
                        subCaption : "All marked tags",
                        showValues:"1",
                        showPercentInTooltip : "0",
                        numberPrefix : "$",
                        enableMultiSlicing:"1",
                        theme: "fusion"
                    },
                    data: null/*[{
                        "label": "Equity",
                        "value": "300000"
                    }, {
                        "label": "Debt",
                        "value": "230000"
                    }, {
                        "label": "Bullion",
                        "value": "180000"
                    }, {
                        "label": "Real-estate",
                        "value": "270000"
                    }, {
                        "label": "Insurance",
                        "value": "20000"
                    }]*/
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
        /** Pie chart */
        axios.get('/data/utils/charts?scope=tags&period=all').then(response => {
            console.log('Pie: ')
            console.log(response.data)
            var data = []
            for (let [key, value] of Object.entries(response.data.data)) {
                data.push({label: key, value: value})
            }
            this.dataSource.pie.data = data
        })
        const data = [
                        [
                            "01-Feb-11",
                            8866
                        ],
                        [
                            "02-Feb-11",
                            2174
                        ],
                        [
                            "03-Feb-11",
                            2084
                        ],
                        [
                            "04-Feb-11",
                            1503
                        ],
                        [
                            "05-Feb-11",
                            4928
                        ],
                        [
                            "06-Feb-11",
                            4667
                        ],
                        [
                            "07-Feb-11",
                            1064
                        ],
                        [
                            "08-Feb-11",
                            851
                        ],
                        [
                            "09-Feb-11",
                            7326
                        ],
                        [
                            "10-Feb-11",
                            8399
                        ],
                        [
                            "11-Feb-11",
                            4084
                        ],
                        [
                            "12-Feb-11",
                            4012
                        ],
                        [
                            "13-Feb-11",
                            1673
                        ],
                        [
                            "14-Feb-11",
                            6018
                        ],
                        [
                            "15-Feb-11",
                            9011
                        ],
                        [
                            "16-Feb-11",
                            5817
                        ],
                        [
                            "17-Feb-11",
                            5813
                        ],
                        [
                            "18-Feb-11",
                            566
                        ],
                        [
                            "19-Feb-11",
                            9065
                        ],
                        [
                            "20-Feb-11",
                            6734
                        ],
                        [
                            "21-Feb-11",
                            6937
                        ],
                        [
                            "22-Feb-11",
                            3038
                        ],
                        [
                            "23-Feb-11",
                            4445
                        ],
                        [
                            "24-Feb-11",
                            8782
                        ],
                        [
                            "25-Feb-11",
                            9489
                        ],
                        [
                            "26-Feb-11",
                            9624
                        ],
                        ]
        const schema = [
            {
                "name": "Time",
                "type": "date",
                "format": "%d-%b-%y"
            },
            {
                "name": "Grocery Sales Value",
                "type": "number"
            }
            ]
        const fusionTable = new FusionCharts.DataStore().createDataTable(
            data,
            schema
        )
        this.dataSource.timeseries.data = fusionTable
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