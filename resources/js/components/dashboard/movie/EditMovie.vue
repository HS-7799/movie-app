<template>
  <div class="row" >
      <div class="col-12">
            <div class="float-right">
              <button class="btn btn-primary" @click="$router.push({ name : 'Movies' })" >Back</button>
            </div>
            <div>
              <button class="btn btn-primary" @click="$router.push({ name : 'Edit Cast',params : {id : $route.params.id} })" >
                  Edit cast
              </button>
            </div>
        </div>
      <div class="col-12" >
          <form @submit.prevent="submitForm" class="row" >
              <div class="col-lg-3 col-12 mx-auto">
                  <app-update-poster v-if="poster !== 'not yet'" :poster="poster" ></app-update-poster>
              </div>
                <div class="col-lg-9 col-12">
                <div class="form-group">
                    <label for="title">Title</label>
                    <input id="title" class="form-control" v-model="form.title" type="text">
                    <p class="text-danger" v-if="errors.title" >
                        {{ errors.title[0] }}
                    </p>
                </div>

                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea id="description"  class="form-control" v-model="form.description" rows="3"></textarea>
                    <p class="text-danger" v-if="errors.description" >
                        {{ errors.description[0] }}
                    </p>
                </div>

                <div class="form-group">
                    <label for="rating">Rating</label>
                    <input id="rating" class="form-control" v-model="form.rating" type="text">
                    <p class="text-danger" v-if="errors.rating" >
                        {{ errors.rating[0] }}
                    </p>
                </div>

                <div class="form-group">
                    <label for="release_year">Release year</label>
                    <input id="release_year" class="form-control" v-model="form.release_year" type="text">
                    <p class="text-danger" v-if="errors.release_year" >
                        {{ errors.release_year[0] }}
                    </p>
                </div>

                <div class="form-group">
                    <label for="trailer">Trailer code</label>
                    <input id="trailer" class="form-control" v-model="form.trailer" type="text">
                    <p class="text-danger" v-if="errors.trailer" >
                        {{ errors.trailer[0] }}
                    </p>
                </div>

                <div class="form-group">
                    <label for="categories">Categories</label>
                    <select id="categories" class="form-control" v-model="form.categories" multiple>
                        <option :value="category.id" v-for="category in categories" :key="category.id" >
                            {{ category.name }}
                        </option>
                    </select>
                    <p class="text-danger" v-if="errors.categories" >
                        {{ errors.categories[0] }}
                    </p>
                </div>
                <button type="submit" class="btn btn-primary" >
                    Update
                </button>
                </div>


          </form>
      </div>
  </div>
</template>

<script>
import UpdatePoster from './UpdatePoster.vue'
export default {
    data()
    {
        return {
            form : {
                title : '',
                description : '',
                rating : null,
                trailer : null,
                release_year : null,
                categories : []
            },
            poster : 'not yet',
            errors : {},
            categories : [],
            value: [],
        }
    },
    methods: {
        submitForm()
        {
            axios.put(`/movies/${this.$route.params.id}`,this.form)
            .then((res) => {
                this.$router.push({name : 'Movies'})
            })
            .catch((err) => {
                this.handle(err)
            })
        },
        handle(err)
        {
            if(err.response.data.errors)
            {
                this.errors = err.response.data.errors
            }
            if(err.response.status === 401)
            {
                window.location = '/login'
            }
            if(err.response.status === 404)
            {
                this.$router.push({name : 'Not found'})
            }
            if(err.response.status === 403)
            {
                this.$emit('actionUnauthorized',true)
            }
        }
    },
    components: {
        appUpdatePoster : UpdatePoster
    },
    created()
    {

        if(this.$auth.can('update_movie'))
        {
            axios.get(`/movies/${this.$route.params.id}`)
            .then((res) => {
                this.poster = res.data.poster
                this.form.title = res.data.title
                this.form.description = res.data.description
                this.form.rating = res.data.rating
                this.form.trailer = res.data.trailer
                this.form.release_year = res.data.release_year
                this.form.categories = res.data.categories.map((category) => {
                    return category.id;
                })
            })
            .catch((err) => {
                this.handle(err)
            })

            axios.get(`/categories`)
            .then((res) => {
                this.categories = res.data.data

            }).catch((err) => {
                this.handle(err)
            });
        }
        else
        {
            this.$router.push({name : 'dahsboard'})
        }
    }

}
</script>

<style>

</style>


