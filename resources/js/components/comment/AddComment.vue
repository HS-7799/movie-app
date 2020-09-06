<template>
    <div>
        <p v-if="errors.body" class="alert alert-danger" >
              {{ errors.body[0] }}
        </p>
        <form @submit.prevent="submitForm" class="form-inline my-2" >
            <input  type="text"
                    class="form-control col-11 mr-1"
                    required
                    :class="{'is-invalid' : errors.body}"
                    v-model="form.body"
                    :placeholder="commentId ? 'Add reply' : 'Add comment'" >
            <button class="btn" :class="{'disabled' : (!form.body) || waitForResponse}" >
                Add
                <span v-if="waitForResponse" class="spinner-border spinner-border-sm"></span>
            </button>
        </form>
    </div>
</template>

<script>

export default {
    props : {
        movieId : {
            type : String,
            default : ''
        },
        commentId : {
            type : String,
            default : null
        }
    },
    data()
    {
        return {
            form : {
                body : null,
                comment_id : this.commentId
            },
            errors : {},
            waitForResponse : false

        }
    },
    methods : {
        submitForm()
        {

            if(!this.waitForResponse)
            {
                this.addComment();
            }
        },
        spliceComment(index)
        {
            this.comments.data.splice(index,1)
        },
        handle(err)
        {
            if(err.response.data.errors)
            {
                this.errors = err.response.data.errors
            }
            if(err.response.status === 404)
            {
                this.$router.push({name : 'Not found'})
            }
            if(err.response.status === 401)
            {
                    this.$swal.fire({
                    // title: 'Are you sure?',
                    text: "You need to login",
                    // icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Login'
                    }).then((result) => {
                        if (result.value) {
                            window.location = '/login';
                        }
                    })
            }
        },
        addComment()
        {
            this.waitForResponse = true;
            if (this.form.body) {
                axios.post(`/comments/${this.movieId}`,this.form)
                .then((res) => {
                    this.waitForResponse = false;
                    this.$emit('newComment',res.data)
                    this.form.body = null
                    this.errors = {}
                }).catch((err) => {
                    this.waitForResponse = false;
                    this.handle(err)
                });
            }
        },

    },
};

</script>

<style scoped >
input
{
    border-radius : 0;
    margin : 2px
}
button
{
    margin:2px;
    background-color : #4bb8ce;
    color : white
}
</style>
