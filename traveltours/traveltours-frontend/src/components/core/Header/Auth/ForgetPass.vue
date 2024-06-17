<template>
  <v-card class="rounded-bl-xl" flat dark color="#003C71CC">
    <v-card-text>
      <v-form ref="form">
        <v-text-field
          label="Email"
          name="email"
          prepend-icon="mdi-email"
          type="text"
          autofocus
          color="secondary"
          clearable
          v-model="email"
        />
      </v-form>
    </v-card-text>
    <v-card-actions>
      <v-spacer />
      <v-btn fab small outlined class="mt-n5 mb-2" color="secondary" @click="sendEmail">
        <v-icon>mdi-send</v-icon>
      </v-btn>
      <v-spacer />
    </v-card-actions>
    <v-snackbar v-model="snackbar" :timeout="timeout" :color="snackbarColor">
      {{ snackbarMessage }}
    </v-snackbar>
  </v-card>
</template>

<script>
import { apiService } from '@/common/service/api.js';

export default {
  data() {
    return {
      email: '',
      snackbar: false,
      snackbarMessage: '',
      snackbarColor: '',
      timeout: 3000,
    };
  },
  methods: {
    sendEmail() {
      if (!this.email) {
        this.snackbarMessage = 'Please enter your email';
        this.snackbarColor = 'error';
        this.snackbar = true;
        return;
      }

      apiService.post('/send-email', { email: this.email })
        .then(response => {
          if (response && response.data) {
            console.log('Email sent successfully:', response.data);
            this.snackbarMessage = 'Email sent successfully';
            this.snackbarColor = 'success';
            this.snackbar = true;
            this.email = ''; // Clear email input
          } 
        })
        .catch(error => {
          console.error('Failed to send email:', error);
          if (error.response) {
            // Server responded with a status code outside of 2xx range
            this.snackbarMessage = `Failed to send email: ${error.response.data.message}`;
          } else if (error.request) {
            // The request was made but no response was received
            this.snackbarMessage = 'Failed to send email: No response received';
          } else {
            // Something happened in setting up the request that triggered an Error
            this.snackbarMessage = 'Failed to send email: Request setup error';
          }
          this.snackbarColor = 'error';
          this.snackbar = true;
        });
    }
  } 
};
</script>
