<template>
    <div class="row" >
        <div class="col-12" >
            <div class="mb-1 float-right">
              <button class="btn btn-primary" @click="$router.push({name: 'Actors'})" >back</button>
        </div>
        </div>
        <div class="card col-12" >
            <form @submit.prevent="addActor" >
                <div class="form-group">
                    <label for="photo">Actor photo</label>
                    <input id="photo" class="form-control-file" type="file" @change="selectFile" >
                    <p class="text-danger" v-if="errors.photo" >
                        {{ errors.photo[0] }}
                    </p>
                </div>
                <div class="form-group">
                    <label for="name">Actor name</label>
                    <input id="name" class="form-control" v-model="name" type="text" >
                    <p class="text-danger" v-if="errors.name" >
                        {{ errors.name[0] }}
                    </p>
                </div>
                <div class="form-group">
                    <label for="biographie">Actor biographie</label>
                    <textarea id="biographie" class="form-control" v-model="biographie" rows="4"></textarea>
                    <p class="text-danger" v-if="errors.biographie" >
                        {{ errors.biographie[0] }}
                    </p>
                </div>
                <button class="btn btn-primary" type="submit">Add</button>

            </form>
        </div>
    </div>

</template>


<script>
export default {

    data()
    {
        return {
            photo : null,
            name: '',
            biographie : '',
            errors : {}
        };
    },
    methods : {
        selectFile($event)
        {
            this.photo = $event.target.files[0]
        },
        addActor()
        {
            const form = new FormData();
            form.append('photo',this.photo)
            form.append('name',this.name)
            form.append('biographie',this.biographie)
            axios.post('/actors',form)
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
            if(err.response.status === 404)
            {
                this.$router.push({name : 'Not found'})
            }
        },
    },
    created()
    {
        if(!this.$auth.can('create_actor'))
        {
            this.$router.push('/dashboard/home');
        }
    }

}
</script>


<style scoped>

</style>


