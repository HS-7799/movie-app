<template>
  <div class="row" >
      <div class="col-12">
            <div class="float-right">
              <button class="btn btn-primary" @click="$router.push({ name : 'Edit Movie',params : { id : $route.params.id } })" >Back</button>
            </div>
        </div>
      <div class="col-12" >
          <div v-if="selectedActors.length > 0" >
              <h5>Cast</h5>
            <form @submit.prevent="submitForm" class="my-1" >
                <div class="row">
                    <div class="col-lg-3 col-md-4 col-6" v-for="actor in selectedActors" :key="actor.id" >
                        <img :src="actor.photo" width="100px" alt="">
                        <h4>{{ actor.name }}</h4>
                        <input type="text" class="form-control" placeholder="Name in movie" v-model="names[actor.id]">
                        <p class="text-danger" v-if="errors[actor.id]" >
                            {{ errors[actor.id].role[0] }}
                        </p>
                    </div>
                </div>
                <div class="form-group text-right">
                    <button type="submit" class="btn btn-primary" :class="{'disabled' : showSpinner}" >
                        Update
                        <span v-if="showSpinner" class="spinner-border spinner-border-sm"></span>
                    </button>
                </div>
            </form>
          </div>
        <input type="text" v-model="filterText" placeholder="Search an acotor..." class="form-control">
          <ul class="list-group my-1">
                <li class="list-group-item p-0"
                    :class="{'active' : selectedActors.includes(actor)}"
                    v-for="(actor,index) in filteredActors"
                    :key="actor.id"
                >
                    <input type="checkbox"
                        v-model="selectedActors"
                        :id="'check'+index"
                        class="form-check-input"
                        :value="actor"
                    />
                    <label :for="'check'+index"   >
                        <img :src="actor.photo" width="50px" class="m-2" alt="">
                        <span>{{ actor.name }}</span>
                    </label>

                </li>
          </ul>
      </div>
  </div>
</template>

<script>
export default {

    data()
    {
        return {
            actors : [],
            selectedActors : [],
            cast : [],
            names : {},
            filterText : '',
            showSpinner : false,
            errors : {}
        }
    },
     methods : {
        submitForm()
        {
            var difference = this.cast.filter(x => !this.selectedActors.includes(x)).map((actor) => {
                return actor.id
            })
            this.showSpinner = true
            const updatedActors = this.selectedActors.map((actor) => {
                const form = new FormData()
                form.append('existingActors',difference)
                form.append('role',this.names[actor.id])
                return axios.post(`/movies/${this.$route.params.id}/cast/${actor.slug}`,form)
                .then((res) => {
                    this.errors = {}
                }).catch((err) => {
                    if(err.response.data.errors)
                    {
                        this.errors[actor.id] = err.response.data.errors
                    }
                    this.handle(err)
                });
            })

            axios.all(updatedActors)
            .then((result) => {
                this.showSpinner = false
            }).catch((err) => {
                this.handle(err)
            });
        },
        handle(err)
        {

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
    created()
    {
        if(this.$auth.can('update_movie'))
        {
            axios.get(`/movies/${this.$route.params.id}`)
            .then((res) => {
                // console.log()
                this.selectedActors = res.data.actors.map((actor) => {
                    this.names[actor.id] = actor.pivot.role
                    return {
                        id : actor.id,
                        name : actor.name,
                        photo : actor.photo,
                        slug : actor.slug
                    }
                })
                this.cast = this.selectedActors

            })
            .catch((err) => {
                this.handle(err)
            })

            axios.get(`/actors`)
            .then((res) => {
                this.actors = res.data.map((actor) => {
                    return {
                        id : actor.id,
                        name : actor.name,
                        photo : actor.photo,
                        slug : actor.slug
                    }
                })
            })
            .catch((err) => {
                this.handle(err)
            })
        }
        else
        {
            this.$router.push({name : 'dahsboard'})
        }
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
label
{
    cursor: pointer;
    width: 100%;
    margin: 0;
}
</style>

