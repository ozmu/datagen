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
                            <div class="d-flex">
                                <h3 class="font-weight-semibold mb-0">{{ widgets.balance.balance }}<b-icon icon="currency-try" custom-size="mdi-18px"></b-icon></h3>
                                <div class="list-icons ml-auto">
                                    <a class="list-icons-item" data-action="reload"></a>
                                </div>
                            </div>
                            <div>
                                Balance
                                <div class="font-size-sm opacity-75">{{ widgets.balance.notVerifiedBalance }}<b-icon icon="currency-try" custom-size="mdi-14px"></b-icon></div>
                            </div>
                        </div>
                    </div>
                    <!-- /today's revenue -->

                </div>

                <div class="col-lg-4">
                    <!-- Today's revenue -->
                    <div class="card bg-blue-400 home-widget">
                        <div class="card-body">
                            <div class="d-flex">
                                <h3 class="font-weight-semibold mb-0">$18,390</h3>
                                <div class="list-icons ml-auto">
                                    <a class="list-icons-item" data-action="reload"></a>
                                </div>
                            </div>
                            
                            <div>
                                Today's revenue
                                <div class="font-size-sm opacity-75">$37,578 avg</div>
                            </div>
                        </div>
                    </div>
                    <!-- /today's revenue -->

                </div>

                <div class="col-lg-4">
                    <!-- Members online -->
                    <div class="card bg-teal-400 home-widget">
                        <div class="card-body">
                            <div class="d-flex">
                                <h3 class="font-weight-semibold mb-0">3,450</h3>
                                <span class="badge bg-teal-800 badge-pill align-self-center ml-auto">+53,6%</span>
                            </div>
                            
                            <div>
                                Members online
                                <div class="font-size-sm opacity-75">489 avg</div>
                            </div>
                        </div>
                    </div>
                    <!-- /members online -->

                </div>
            </div>
            <!-- /quick stats boxes -->

            <!-- Marketing campaigns -->
            <div class="card">
                <div class="card-header header-elements-sm-inline">
                    <h6 class="card-title">Marketing campaigns</h6>
                    <div class="header-elements">
                        <span class="badge bg-success badge-pill">28 active</span>
                        <div class="list-icons ml-3">
                            <div class="dropdown">
                                <a href="#" class="list-icons-item dropdown-toggle" data-toggle="dropdown"><i class="icon-menu7"></i></a>
                                <div class="dropdown-menu">
                                    <a href="#" class="dropdown-item"><i class="icon-sync"></i> Update data</a>
                                    <a href="#" class="dropdown-item"><i class="icon-list-unordered"></i> Detailed log</a>
                                    <a href="#" class="dropdown-item"><i class="icon-pie5"></i> Statistics</a>
                                    <div class="dropdown-divider"></div>
                                    <a href="#" class="dropdown-item"><i class="icon-cross3"></i> Clear list</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-body d-sm-flex align-items-sm-center justify-content-sm-between flex-sm-wrap">
                    <div class="d-flex align-items-center mb-3 mb-sm-0">
                        <div id="campaigns-donut"></div>
                        <div class="ml-3">
                            <h5 class="font-weight-semibold mb-0">38,289 <span class="text-success font-size-sm font-weight-normal"><i class="icon-arrow-up12"></i> (+16.2%)</span></h5>
                            <span class="badge badge-mark border-success mr-1"></span> <span class="text-muted">May 12, 12:30 am</span>
                        </div>
                    </div>

                    <div class="d-flex align-items-center mb-3 mb-sm-0">
                        <div id="campaign-status-pie"></div>
                        <div class="ml-3">
                            <h5 class="font-weight-semibold mb-0">2,458 <span class="text-danger font-size-sm font-weight-normal"><i class="icon-arrow-down12"></i> (-4.9%)</span></h5>
                            <span class="badge badge-mark border-danger mr-1"></span> <span class="text-muted">Jun 4, 4:00 am</span>
                        </div>
                    </div>

                    <div>
                        <a href="#" class="btn bg-indigo-300"><i class="icon-statistics mr-2"></i> View report</a>
                    </div>
                </div>
            </div>
            <!-- /marketing campaigns -->

        </div>
    </div>
    <!-- /dashboard content -->
</template>

<script>
export default {
    data(){
        return {
            widgets: {
                balance: {}
            },
            msg: 'Hello World'
        }
    },

    mounted(){
        this.getWidgets("balance")
    },

    methods: {
        getWidgets(scope){
            axios.get('/data/utils/widgets?scope=' + scope).then(response => {
                this.widgets[scope] = response.data
            })
        }
    }
}
</script>

<style scoped>
.home-widget {
    color: #000000 !important;
}
</style>