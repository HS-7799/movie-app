<template>
    <div class="row">
        <div class="col-12">
            <div class="float-right">
              <button class="btn btn-primary" @click="$router.push('/dashboard/movies')" >Back</button>
            </div>
        </div>
        <div class="col-12">
            <div class="card">
            <div class="alert alert-danger" v-for="(error,index) in errors" :key="index"  >
                    {{ error.video[0] }}
                    <a href="" @click.prevent="reUpload" v-if="videos.length === 0"  >Upload again</a>
            </div>
                <div class="card-body">
                    <div v-if="!isUploading"  class="row justify-content-center">
                        <img src="/images/video.png"   @click="$refs.videos.click()" id="uploadVideo" alt="upload movies">
                        <input type="file" ref="videos" multiple @change="uploadVideos" >
                    </div>
                    <div v-else>
                        <div v-for="video in videos " :key="video.name" >


                            <div class="progress">
                                <div class="progress-bar" :style="{ width : (progress[video.name] || video.percentage)+'%' }">
                                    {{ video.percentage ? video.percentage === 100 ? 'Video processing completed' : 'Processing' : 'Uploading' }}

                                </div>
                            </div>
                            <div class="row">
                                <div class="col-4">
                                   <div :class="{'thumbnail' : !video.thumbnail }" >
                                       <span v-if="!video.thumbnail" >Loading thumbnail...</span>
                                       <img v-else  :src="'/storage'+video.thumbnail" width="100%" alt="">
                                   </div>
                                </div>
                                <div class="col-8">
                                   <h4> {{ video.name || video.title }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {

    data()
    {
        return {
            videos : [],
            uploads : [],
            isUploading : false,
            progress : {},
            intervals : {},
            errors : {}
        }
    },
    methods : {
        reUpload()
        {
            this.isUploading = false
            this.errors = {}
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
        },
        uploadVideos()
        {
            this.isUploading = true;
            this.videos = Array.from(this.$refs.videos.files);
            const uploaders = this.videos.map((video) => {

                const form = new FormData();

                form.append('title',video.name);
                form.append('video',video);

                this.progress[video.name] = 0;

                return axios.post(`/movies`,form,{
                    onUploadProgress : (event) => {
                        this.progress[video.name] = Math.ceil((event.loaded/event.total)*100)
                        this.$forceUpdate();
                    }
                })
                .then((res) => {
                    this.uploads.push(res.data)
                }).catch((err) => {
                    if(err.response.data.errors)
                    {
                        this.errors[video.name] = err.response.data.errors
                    }
                    this.progress[video.name] = 0;
                    this.handle(err)
                });

            });

            axios.all(uploaders)
            .then(() => {
                this.videos = this.uploads

                this.videos.forEach((video) => {

                    this.intervals[video.id] = setInterval(() => {
                        axios.get(`/movies/${video.id}`)
                        .then((res) => {
                            if(res.data.percentage === 100)
                            {
                                clearInterval(this.intervals[video.id])
                            }

                            this.videos = this.videos.map((v) => {
                                if(v.id === res.data.id)
                                {
                                    return res.data
                                }

                                return v;
                            })
                        })
                        .catch((err) => {
                             this.handle(err)
                        })
                    },3000);
                })

            }).catch((err) => {
                 this.handle(err)
            });
        }
    },
    created()
    {
        if(!this.$auth.can('create_movie'))
        {
            this.$router.push('/dashboard/home')
        }
    }



}
</script>

<style scoped >

.thumbnail
{
    height : 150px;
    background-color: gray;
    color: white;
    text-align: center;
}
#uploadVideo
{
    cursor:pointer;
}

input
{
    display:none
}


</style>


