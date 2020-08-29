<template>
    <div class="row" >
        <div class="col-12" >
            <div class="mb-1 float-right">
              <button class="btn btn-primary" @click="$router.push({name: 'Actors'})" >back</button>
        </div>
        </div>
        <div class="card col-12" >
            <form @submit.prevent="updateActor" >
                <app-update-photo v-if="photo !== 'no photo'" :photo="photo" ></app-update-photo>
                <div class="form-group">
                    <label for="name">Actor name</label>
                    <input id="name" class="form-control" v-model="form.name" type="text" >
                    <p class="text-danger" v-if="errors.name" >
                        {{ errors.name[0] }}
                    </p>
                </div>
                <div class="form-group">
                    <label for="biographie">Actor biographie</label>
                    <textarea id="biographie" class="form-control" v-model="form.biographie" rows="4"></textarea>
                    <p class="text-danger" v-if="errors.biographie" >
                        {{ errors.biographie[0] }}
                    </p>
                </div>
                <button class="btn btn-primary" type="submit">Update</button>

            </form>
        </div>
    </div>

</template>


<script>
import UpdatePhoto from "./UpdateActorPhoto.vue";
export default {

    data()
    {
        return {
            photo : 'no photo',
            form : {
                name: '',
                biographie : '',
            },
            errors : {}
        };
    },
    methods : {
        selectFile($event)
        {
            this.photo = $event.target.files[0]
        },
        updateActor()
        {

            axios.put(`/actors/${this.$route.params.slug}`,this.form)
            .then((res) => {
                this.errors = {}
                this.$router.push({name : 'Actors'});
            }).catch((err) => {
                this.handle(err)
            });
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
            if(err.response.status === 403)
            {
                this.$emit('actionUnauthorized',true)
            }
        },
    },
    components : {
        appUpdatePhoto : UpdatePhoto
    }
    ,
    created()
    {
        if(this.$auth.can('update_actor'))
        {
            axios.get(`/actors/${this.$route.params.slug}`)
            .then((res) => {
                this.photo = res.data.photo
                this.form.name = res.data.name
                this.form.biographie = res.data.biographie
                console.log(this.form);
            }).catch((err) => {
                this.handle(err)
            });
        } else {
            this.$router.push('/dashboard/home');
        }
    }

}
</script>


<style scoped>

</style>


