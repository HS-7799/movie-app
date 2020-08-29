<template>
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
                <div class="content-header">
                 <div class="container-fluid">
                    <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">{{ $route.name }}</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <router-link to="/dashboard/home" >Dashboard</router-link>
                        </li>
                        <li v-if="$route.name !== 'Dashboard'" class="breadcrumb-item active">{{ $route.name }}</li>
                        </ol>
                    </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
                </div>
                <!-- /.content-header -->
        <div class="content">
            <div class="container-fluid">
                <div class="row" v-if="noAccess">
                    <div class="col-12">
                        <div class="alert alert-warning alert-dismissible fade show"  role="alert">
                            <strong>This action is unauthorized.</strong>
                            <button type="button" class="close" @click="noAccess=false" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    </div>
                </div>
                <router-view @actionUnauthorized="noAccess = $event" ></router-view>
                <vue-progress-bar></vue-progress-bar>
            </div>
        </div>
    </div>
</template>

<script>
export default {

    data()
    {
        return {
            noAccess : false
        }
    },

    mounted () {
        this.$Progress.finish()
    },
    created () {
        this.$Progress.start()
        this.$router.beforeEach((to, from, next) => {
        if (to.meta.progress !== undefined) {
            let meta = to.meta.progress
            this.$Progress.parseMeta(meta)
        }
        this.$Progress.start()
        next()
        })
        this.$router.afterEach((to, from) => {
            this.$Progress.finish()
        })
    }
}
</script>

<style>

</style>
