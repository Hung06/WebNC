<template>
  <v-select
    v-model="selection"
    hide-details
    item-text="label"
    :items="duration"
    prepend-icon="mdi-calendar-clock"
    label="Khoảng thời gian"
    :class="{ 'trn-no-border': hasSelectionValue() }"
  >
    <template #selection="{ item }">
      <v-chip color="primary" close @click:close="selection = ''">{{
        item.label
      }}</v-chip>
    </template>
  </v-select>
</template>

<script>
  import syncSelectionWithUrl from './mixins/syncSelectionWithUrl';
  import { multipleSelection } from './mixins/selection';

  export default {
    mixins: [syncSelectionWithUrl('duration'), multipleSelection('duration')],

    data: () => ({
      duration: [
        { value: ['1', '5'], label: '1 - 5 Ngày' },
        { value: ['6', '10'], label: '6 - 10 Ngày' },
        { value: ['11', '15'], label: '11 - 15 Ngày' },
        { value: ['16', '20'], label: '16 - 20 Ngày' },
        { value: ['21'], label: '21+ Ngày' },
      ],
      selection: '',
    }),
  };
</script>
