<!-- assets/js/components/Resultat.vue -->
<template>
  <v-container>
    <h1>Résultat</h1>
    <p>Vous avez soumis : {{ submittedText }}</p>
  </v-container>
</template>

<script>
export default {
  computed: {
    submittedText() {
      return this.$route.query.text;
    },
  },
};
</script>
