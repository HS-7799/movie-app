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
            <button class="btn btn-primary mt-1" :class="{'disabled' : !form.body}" >Add</button>
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

        }
    },
    methods : {
        submitForm()
        {
            this.addComment();
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
            if (this.form.body) {
                axios.post(`/comments/${this.movieId}`,this.form)
                .then((res) => {
                    this.$emit('newComment',res.data)
                    this.form.body = null
                    this.errors = {}
                }).catch((err) => {
                    this.handle(err)
                });
            }
        },

    },
};

</script>

<style></style>
