<!-- assets/js/components/Principale.vue -->
<template>
  <v-container>
    <v-text-field v-model="text" label="Entrez votre texte" />
    <v-btn @click="submitText">Soumettre</v-btn>
  </v-container>
</template>

<script>
export default {
  data() {
    return {
      text: '',
    };
  },
  methods: {
    submitText() {
      // Envoie le texte au routeur
      this.$router.push({ path: '/resultat', query: { text: this.text } });
    },
  },
};
</script>
