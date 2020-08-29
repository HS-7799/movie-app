<template>
    <div class="row" >
        <div class="card col-12" >
            <div>
            </div>
            <div class="row">
                <div class="col-md-10 col-sm-7">
                    <input type="text" v-model="filterText"  class="form-control" placeholder="Search an actor...">
                </div>
                <div  class="col-md-2 col-sm-5" v-if="$auth.can('create_actor')" >
                <router-link :to="{ name : 'Create Actor'}" tag="button" class="btn btn-primary" >Add</router-link>
                </div>
            </div>
            <div v-if="showActors" >
                <table v-if="actors.length > 0" class="mt-1 table table-light table-bordered">
                    <thead class="thead-dark">
                        <tr>
                            <th class="text-center" >Photo</th>
                            <th>Name</th>
                            <th>Biographie</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(actor,index) in filteredActors" :key="actor.id" >
                            <td class="text-center">
                                <img v-if="actor.photo" :src="actor.photo" width="70px" />
                                <img v-else src="/images/noImage.png" width="70px" />

                            </td>
                            <td>
                                {{ actor.name }}
                            </td>
                            <td class="text" :title="actor.biographie" >
                                <span>
                                    {{ actor.biographie }}
                                </span>
                            </td>
                            <td v-if="$auth.can('delete_actor') || $auth.can('update_actor') " >
                                <router-link
                                    :to="{ name : 'Edit Actor',params : { slug : actor.slug }}"
                                    v-if="$auth.can('update_actor')"
                                    class="btn btn-warning">
                                    <i class="fas fa-edit"></i>
                                </router-link>
                                <button
                                        v-if="$auth.can('delete_actor')"
                                        class="btn btn-danger"
                                        title="delete this actor"
                                        @click="startDelete(actor.slug,index)"
                                >
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </td>
                            <td v-else >No Actions</td>
                        </tr>
                    </tbody>
                </table>
                <div v-else >
                    No actors
                </div>
                <div class="row justify-content-center" >
                    <sliding-pagination
                        :current="current_page"
                        :total="total"
                        @page-change="loadActors">
                    </sliding-pagination>
                </div>
            </div>
            <div v-else class="row justify-content-center">
                <div class="spinner-border text-primary"></div>
            </div>

        </div>
    </div>
</template>

<script>
export default {

    data()
    {
        return {
            actors : [],
            filterText : '',
            current_page : 1,
            total : 0,
            showActors : false
        }
    },
    methods : {
        startDelete(slug,index)
        {
            this.$swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.value) {
                    this.deleteActor(slug,index)
                }
            })
        },
        loadActors(page)
        {
            axios.get(`/actors?page=${page}`)
            .then((res) => {
                this.showActors = true
                this.current_page = page
                this.actors = res.data.data
                this.total = res.data.last_page
            }).catch((err) => {
                if(err.response.status === 403)
                {
                    this.$emit('action Unauthorized',true)
                }
            });
        },
        deleteActor(slug,index)
        {
            axios.delete(`/actors/${slug}`)
            .then((result) => {
                this.successMessage('Actor has been deleted');
                this.actors.splice(index,1)
                if(this.actors.length === 0 && this.current_page > 1 )
                {
                    this.current_page -= 1
                }
                this.loadActors(this.current_page)
            }).catch((err) => {
                console.log(err.response);
            });
        },
        successMessage(msg)
        {
            this.$swal.fire({
                icon: 'success',
                title: msg,
                showConfirmButton: false,
                timer: 1000
            })
        },
    },
    created()
    {
        this.$auth.can('manage_actor') ? this.loadActors(this.current_page) : this.$router.push({name : 'Dashboard'})
    },
    computed:
    {
        filteredActors()
        {
            return this.actors.filter((actor) => {
                return actor.name.toLowerCase().match(this.filterText.toLowerCase())
            });
        }
    }

}
</script>


<style scoped >
.table td.text {
    max-width: 177px;
}
.table td.text span {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    display: inline-block;
    max-width: 100%;
}
</style>



