<template>
    <div class="row">
        <div class="card col-12">
            <div class="row">
                <div class="col-md-10 col-sm-7">
                    <input type="text" v-model="filterText"  class="form-control" placeholder="Search a movie...">
                </div>
                <div  class="col-md-2 col-sm-5" v-if="$auth.can('create_movie')" >
                    <router-link tag="button" to="/dashboard/movies/upload" class="btn btn-primary">
                        Upload movie
                    </router-link>
                </div>
            </div>
            <div v-if="showMovies" >
                <table v-if="movies.length > 0"  class="mt-1 table table-light table-bordered" >
                    <thead class="thead-dark">
                        <tr>
                            <th>Poster</th>
                            <th>Thumbnail</th>
                            <th>Title</th>
                            <th>Categories</th>
                            <th>Release year</th>
                            <th>State</th>
                            <th>Actions</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(movie,index) in filteredMovies" :key="movie.id" >
                            <td>
                                <img v-if="movie.poster" :src="movie.poster" width="100px" />
                                <span v-else >No poster available</span>
                            </td>
                            <td>
                                <img v-if="movie.thumbnail" :src="`/storage/${movie.thumbnail}`" width="100px" />
                                <div v-else style="width:100px" class="bg-secondary text-center text-white" >
                                    Loading thumbnail...
                                </div>
                            </td>
                            <td>{{ movie.title }}</td>
                            <td v-if="movie.categories.length > 0" >
                                <span v-for="category in movie.categories" :key="category.id"
                                    class="p-2 mb-1 mr-1 badge badge-info"
                                >{{ category.name }}
                                </span>
                            </td>
                            <td v-else >No categories</td>

                            <td>
                                <span v-if="movie.release_year" >{{ movie.release_year }}</span>
                            </td>

                            <td>
                                {{ movie.percentage === 100 ? 'Live' : 'Processing' }}
                            </td>


                            <td v-if="$auth.can('update_movie') || $auth.can('delete_movie')" >
                                <router-link
                                        v-if="$auth.can('update_movie')"
                                        tag="button"
                                        :to="{name : 'Edit Movie',params : { id : movie.id }}"

                                        class="btn btn-warning"
                                        title="update movie"
                                >
                                    <i class="fas fa-edit"></i>
                                </router-link>
                                <button v-if="$auth.can('delete_movie')"
                                        class="btn btn-danger"
                                        title="delete"
                                        @click="startDelete(movie.id,index)"
                                >
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </td>
                            <td v-else>No actions</td>
                            <td>
                                <a v-if="movie.percentage === 100" :href="`/movies/${movie.id}`" class="btn btn-primary" target="_blank"  >View</a>
                                <a v-if="movie.trailer" :href="`https://www.youtube.com/watch?v=${movie.trailer}`" class="btn btn-danger" target="_blank"  >trailer</a>
                            </td>


                        </tr>
                    </tbody>
                </table>
                <div v-else >
                    No movies
                </div>
                <div class="row justify-content-center" >
                    <sliding-pagination
                    :current="current_page"
                    :total="total"
                    @page-change="loadPage"
                ></sliding-pagination>
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
            filterText : '',
            movies : [],
            current_page : 1,
            total : 0,
            showMovies : false
        }
    },
    computed : {
        filteredMovies()
        {
            return this.movies.filter((movie) => {
                return movie.title.toLowerCase().match(this.filterText.toLowerCase())
            })
        }
    },
    methods : {
        startDelete(id,index)
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
                this.deleteMovie(id,index)
            }
            })
        },
        deleteMovie(id,index)
        {
            axios.delete(`/movies/${id}`)
            .then((result) => {

                 this.successMessage('Movie has been deleted');
                 this.movies.splice(index,1);
                 if(this.movies.length === 0 && this.current_page > 1 )
                 {
                     this.current_page -= 1
                 }
                 this.loadPage(this.current_page)
            }).catch((err) => {
                console.log(err)
                this.handle(err)
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
        loadPage(page)
        {
                axios.get(`/?page=${page}`)
                .then((res) => {
                    this.showMovies = true
                    this.current_page = page
                    this.total = res.data.last_page
                    this.movies = res.data.data
                }).catch((err) => {
                    console.log(err.response.data)
                    this.handle(err)
                });
        },
        handle(err)
        {
            if(err.response.status === 401)
            {
                window.location = '/login'
            }
            if(err.response.status === 403)
            {
                this.$emit('actionUnauthorized',true)
            }
        },
    },
    created()
    {
        this.$auth.can('manage_movie') ? this.loadPage(this.current_page) : this.$router.push('/dashboard/home');
    },


}
</script>

<style>

</style>
