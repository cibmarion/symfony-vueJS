<template>
  <div>
    <ul v-if="feedback.length">
      <li v-for="f in feedback" :key="f['@id']">
        {{ f.author }}
        <vue-star-rating :rating="f.rating" :read-only="true"></vue-star-rating>
        {{ f.comment }}
      </li>
    </ul>
    <p v-else>No feedback yet!</p>
    <p v-if="sent">Thanks for rating this talk!</p>
      <form v-else @submit.prevent="onSubmit">
        <div class="form-group">
          <label for="exampleFormControlInput1">Auteur :</label>
          <input v-model="author" name="author" placeholder="Author" id="exampleFormControlInput1">
        </div>
        <div class="form-group">
          <label>Notes : </label>
          <vue-star-rating v-model="rating"></vue-star-rating>
        </div>
        <div class="form-group">
          <label for="exampleFormControlTextarea1">Commentaires :</label>
          <textarea v-model="comment" name="comment" placeholder="This talk was...." class="form-control" id="exampleFormControlTextarea1" rows="3"></textarea>
        </div>
        <button class="btn btn-primary" :disabled="!author || !rating || !comment" type="submit">Ajouter</button>
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
      fetch(`/api/articles/${this.articleId}/feedback`)
      .then(response => response.json())
      .then(data => this.feedback = data[`hydra:member`])
    },
    onSubmit(){
      const{articleId, author, rating, comment} = this;
      fetch(`/api/feedback`, {
        method: 'POST',
        headers: {'Content-Type': `application/ld+json`},
        body: JSON.stringify({article: `/api/articles/${articleId}`, author, rating, comment})
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