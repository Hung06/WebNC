<template>
  <v-card class="rounded-bl-xl" flat dark color="#003C71CC">
    <v-card-text>
      <v-form ref="form">
        <v-text-field
          v-model="name"
          label="Họ Và Tên"
          name="name"
          prepend-icon="mdi-account"
          type="text"
          clearable
          autofocus
          color="secondary"
        />
        <v-text-field
          v-model="username"
          label="Tên Đăng Nhập"
          name="username"
          prepend-icon="mdi-account-plus"
          type="text"
          clearable
          autofocus
          color="secondary"
        />
        <v-text-field
          v-model="email"
          label="Email"
          name="email"
          prepend-icon="mdi-email"
          type="text"
          clearable
          color="secondary"
        />

        <v-text-field
          id="password"
          v-model="password"
          label="Mật Khẩu"
          name="password"
          prepend-icon="mdi-lock"
          :type="showPass ? 'text' : 'password'"
          clearable
          :append-icon="showPass ? 'mdi-eye' : 'mdi-eye-off'"
          color="secondary"
          @click:append="showPass = !showPass"
        />
        <v-text-field
          id="passwordConfirm"
          v-model="passwordConfirm"
          label="Nhập Lại Mật Khẩu"
          name="passwordConfirm"
          prepend-icon="mdi-lock"
          :type="showPass ? 'text' : 'password'"
          :append-icon="showPass ? 'mdi-eye' : 'mdi-eye-off'"
          clearable
          color="secondary"
          @click:append="showPass = !showPass"
        />
        <v-text-field
          id="photo"
          v-model="photo"
          label="Ảnh Đại Diện"
          name="photo"
          prepend-icon="mdi-face-outline"
          type="text"
          clearable
          color="secondary"
        />
        <v-btn @click="googleSignUp()" color="primary">
          <v-icon>mdi-google</v-icon>
          Đăng Ký Bằng Google
        </v-btn>
        <v-btn @click="onSubmit()" color="secondary">
          <v-icon>mdi-pen</v-icon>
          Đăng Ký
        </v-btn>
      </v-form>
    </v-card-text>
    <v-card-actions>
      <v-spacer />
      <v-spacer />
    </v-card-actions>
  </v-card>
</template>

<script>

import { REGISTER } from '@/store/type/actions'; // Assuming you have defined this action type in your Vuex store
export default {
  data: () => ({
    name: null,
    username: null,
    email: null,
    password: null,
    passwordConfirm: null,
    photo: null,
    showPass: false, // Add this property for toggling password visibility
  }),
  methods: {
    onSubmit() {
      // Handle regular registration
      this.$store.dispatch(REGISTER, {
        name: this.name,
        email: this.email,
        username: this.username,
        password: this.password,
        passwordConfirm: this.passwordConfirm,
        photo: this.photo,
      }).then(() => {
        this.$router.push({ path: '/authentication' });
      }).catch((error) => {
        console.error('Registration error:', error);
      });
    },
    googleSignUp() {
      // Handle Google sign-up
      const provider = new firebase.auth.GoogleAuthProvider();
      firebase.auth().signInWithPopup(provider)
        .then((result) => {
          const user = result.user;
          this.$store.dispatch(REGISTER, {
            name: user.displayName,
            email: user.email,
            username: user.email.split('@')[0],
            password: user.uid,
            passwordConfirm: user.uid,
            photo: user.photoURL,
          }).then(() => {
            this.$router.push({ path: '/authentication' });
          }).catch((error) => {
            console.error('Google sign-up error:', error);
          });
        }).catch((error) => {
          console.error('Google sign-in error:', error);
        });
    },
  },
};
</script>

<style scoped></style>
