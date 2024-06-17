<template>
  <v-card flat class="TrnAuthCard">
    <v-tabs
      class="rounded-tr-xl"
      background-color="#003C71CC"
      color="secondary"
      center
      grow
      dark
    >
      <v-tab href="#login" class="text-capitalize"> Đăng Nhập </v-tab>
      <v-tab-item value="login">
        <TrnLogin />
      </v-tab-item>

      <v-tab href="#signup" class="text-capitalize"> Đăng Ký </v-tab>
      <v-tab-item value="signup">
        <TrnSignUp />
      </v-tab-item>

      <v-tab href="#forgotPassword" class="text-capitalize">
        Quên Mật Khẩu
      </v-tab>
      <v-tab-item value="forgotPassword">
        <TrnForgetPass />
      </v-tab-item>
    </v-tabs>
  </v-card>
</template>

<script>
  import TrnLogin from './Login.vue';
  import TrnSignUp from './Signup.vue';
  import TrnForgetPass from './ForgetPass.vue';

  export default {
    components: {
      TrnLogin,
      TrnSignUp,
      TrnForgetPass,
    },
    data: () => ({ menu: false }),
  };
</script>

<style>
  .TrnAuthCard {
    width: 400px;
    background-color: #0000 !important;
  }

  .theme--light.v-tabs-items {
    background-color: #0000 !important;
  }

  .v-sheet.v-card {
    border-radius: 0;
  }
</style>
