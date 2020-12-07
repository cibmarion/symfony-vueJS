<template>
  <div>
    <ul v-if="feedback.length">
      <li v-for="f in feedback" :key="f['@id']">
        {{ f.author }}
        <star-rating :rating="f.rating" :read-only="true"></star-rating>
        {{ f.comment }}
      </li>
    </ul>
    <p v-else>No feedback PPP !</p>
    <p v-if="sent">Thanks for rating this talk!</p>
    <form v-else @submit.prevent="onSubmit">
      <div class="form-group">
        <label for="exampleFormControlInput1">Auteur</label>
        <input v-model="author" name="author" placeholder="Author" id="exampleFormControlInput1">
      </div>
      <div class="form-group">
        <star-rating v-model="rating"></star-rating>
      </div>
      <div class="form-group">
        <label for="exampleFormControlTextarea1">Commentaire</label>
        <textarea v-model="comment" name="comment" placeholder="This talk was...." class="form-control" id="exampleFormControlTextarea1" rows="3"></textarea>
      </div>
      <input :disabled="!author || !comment" type="submit" value="Bla">
    </form>
  </div>
</template>

<script>
export default {
  props: ['articleId'],
  data() {
    return {
      feedback: [],
      author: '',
      rating: 0,
      comment: '',
      sent: false,
    }
  },
  created() {
    this.fetchFeedback();
  },
  methods: {
    fetchFeedback() {
      fetch('/api/articles/${this.articleId}/feedback')
      .then(response => response.json())
      .then(data => this.feedback = data['hydra:member'])
    },
    onSubmit(){
      const{articleId, author, comment} = this;
      fetch('/api/feedback', {
        method: 'POST',
        headers: {'Content-Type': 'application/ld+json'},
        body: JSON.stringify({article: '/api/articles/${articleId}', author, comment})
      })
      .then(()=>{
        this.sent = true;
        this.fetchFeedback();
          });
    }
  }
};
</script>

<style>
.center {
  text-align: center;
}
</style>