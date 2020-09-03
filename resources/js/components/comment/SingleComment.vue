<template>
    <div>
        <div class="text-right" v-if="$auth.can('delete_comment') || $auth.id() === comment.user_id" >
            <span class="vote mx-1" @click="deleteComment(comment.id)" >
                <i class="fas fa-trash-alt text-danger icon" ></i>
            </span>
        </div>
        <h5 class="m-0">
            <strong>
                {{ comment.userName }}
            </strong>
        </h5>
        <small class="text-secondary text-small" >
            {{ comment.created_at }}
        </small>
        <p class="px-1 m-0" >
            {{ comment.body }}
        </p>
        <a href="#" @click.prevent="addReplyInput = !addReplyInput" >
            {{ addReplyInput ? 'Cancel' : 'Reply' }}
        </a>
        <app-vote :entityId="comment.id"
                    :votes="comment.votes">
                </app-vote>
        <div class="all-replies" >
            <app-add-comment v-if="addReplyInput"
                         :commentId="comment.id"
                         :movieId="movieId"
                         @newComment="addComment($event)"
            ></app-add-comment>


            <app-replies
                        ref="replies"
                        :comment="comment">
            </app-replies>
        </div>

    </div>
</template>

<script>
import Replies from "./Replies";
import AddComment from './AddComment.vue';
export default {
    props : ['comment','movieId'],
    data()
    {
        return {
            addReplyInput : false,
            commentData : this.comment
        }
    },
    methods : {
        deleteComment(commentId)
        {
            axios.delete(`/comments/${commentId}`)
            .then((res) => {
                this.$emit('commentDeleted')
            }).catch((err) => {
                this.handle(err)
            });
        },
        addComment(comment)
        {
            this.$refs.replies.addReply(comment)
            this.addReplyInput = false
        },
        handle(err)
        {
            if(err.response.status === 403)
            {
                alert('This action is unauthorized')
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
    },
    components : {
        appReplies : Replies,
        appAddComment : AddComment

    }

}
</script>

<style scoped >
.all-replies
{
    margin-left : 10%
}
</style>
