<template>
    <div>

        <div v-if="!replies.next_page_url" class="text-primary text-center" >
            <a href="" @click.prevent="resetReplies" >Hide replies</a>
        </div>
        <div class="rounded " v-for="(reply,index) in replies.data" :key="reply.id" >
            <h5 class="m-0">
                <avatar :username="reply.userName" :customStyle="{display:'inline-block'}" :size="30" ></avatar>
                <strong>
                    {{ reply.userName }}
                </strong>
            </h5>
            <small class="text-secondary text-small" >
                {{ reply.created_at }}
            </small>
            <p class="px-1 my-0" >
                {{ reply.body }}
            </p>
            <app-vote :entityId="reply.id"
                    :votes="reply.votes">
                </app-vote>
        </div>
        <div v-if="replies.next_page_url && comment.repliesCount > 0" class="text-primary text-center" >
            <a href="" @click.prevent="fetchReplies" >See replies</a>
        </div>
    </div>
</template>

<script>

export default {
    props : ['comment'],
    data()
    {
        return {
            replies : {
                data : [],
                next_page_url : `/comments/${this.comment.id}/replies`
            },
        }
    },
    methods : {
        spliceComment(index)
        {
            this.replies.data.splice(index,1)
        },
        resetReplies()
        {
            this.replies =  {
                data : [],
                next_page_url : `/comments/${this.comment.id}/replies`
            }
        },
        fetchReplies()
        {
            axios.get(this.replies.next_page_url)
            .then((res) => {
                this.replies = {
                    ...res.data,
                    data : [
                        ...this.replies.data,
                        ...res.data.data
                    ]
                }
            })
        },
        addReply(reply)
        {
            this.replies.data.push(reply)
        }
    },

}
</script>
