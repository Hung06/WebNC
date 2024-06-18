<template>
  <v-card class="rounded-bl-xl" flat dark color="#003C71CC">
    <v-card-text>
      <v-form ref="form">
        <!-- Your existing username and password fields -->
        <v-text-field
          v-model="username"
          label="Tên Đăng Nhập"
          prepend-icon="mdi-email"
          type="text"
          autofocus
          color="secondary"
          clearable
        />
        <v-text-field
          v-model="password"
          label="Mật khẩu"
          prepend-icon="mdi-lock"
          :type="showPass ? 'text' : 'password'"
          :append-icon="showPass ? 'mdi-eye' : 'mdi-eye-off'"
          color="secondary"
          clearable
          @click:append="showPass = !showPass"
        />
      </v-form>
    </v-card-text>
    <v-card-actions>
      <v-spacer />
      <!-- Button to login with Google -->
      <v-btn
        fab
        small
        outlined
        class="mt-n5 mb-2"
        color="secondary"
        @click="redirectToGoogle"
      >
        <v-icon>mdi-google</v-icon>
      </v-btn>
      <!-- Button to login with username and password -->
      <v-btn
        fab
        small
        outlined
        class="mt-n5 mb-2"
        color="secondary"
        @click="onSubmit"
      >
        <v-icon>mdi-key</v-icon>
      </v-btn>
      <v-spacer />
    </v-card-actions>
    <!-- Snackbar for displaying messages -->
    <v-snackbar v-model="snackbar" :timeout="timeout" :color="snackbarColor">
      {{ snackbarMessage }}
    </v-snackbar>
  </v-card>
</template>

<script>

import { LOGIN } from '@/store/type/actions';
export default {
    name: 'TrnLogin',

    data: () => ({
      showPass: false,
      username: null,
      password: null,
    }),

  methods: {
    async onSubmit() {
        await this.$store.dispatch(LOGIN, {
          username: this.username,
          password: this.password,
        });

        if (
          this.$route.path.includes('/authentication') &&
          this.$store.getters.isAuthenticated
        )
          await this.$router.push('/');
      },

  redirectToGoogle() {
    const clientId = '583293783712-srcp9vklbigg3f4lg03d5tb3jkqr1edv.apps.googleusercontent.com';
    const redirectUri = 'http://localhost:8000/api/auth/google/callback';
    const scope = 'openid profile email';
    const responseType = 'code';
    const state = 'Lwan9IjmADMOEuRWVyBxFdMQGqI9RxPpK1YhEgjx';
    const authUrl = `https://accounts.google.com/o/oauth2/auth?client_id=${clientId}&redirect_uri=${redirectUri}&scope=${scope}&response_type=${responseType}&state=${state}`;
    
    window.location.href = authUrl;
  }
}

};
</script>

<style scoped></style>
