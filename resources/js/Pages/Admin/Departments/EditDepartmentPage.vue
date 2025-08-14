<template>
  <Head title="Edit Department" />
  <vue3-snackbar top right :duration="4000" />

  <div class="p-6 min-h-screen bg-gray-100">
    <!-- Breadcrumb -->
    <nav class="text-sm text-gray-600 mb-6 flex items-center gap-2">
      <i class="fa fa-home text-gray-400"></i>
      <span>/</span>
      <a href="/admin/departments" class="hover:text-blue-600">Departments</a>
      <span>/</span>
      <span class="font-semibold text-blue-700">Edit</span>
    </nav>

    <!-- Card -->
    <div class="bg-white rounded-xl shadow-lg p-8 max-w-3xl mx-auto border border-blue-100">
      <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center gap-2">
        <i class="fa fa-building text-blue-600"></i> Edit Department
      </h2>

      <form @submit.prevent="submit" class="space-y-5">
        <!-- Name -->
        <div>
          <label class="block text-gray-700 font-medium mb-1">Department Name</label>
          <input
            v-model="form.name"
            type="text"
            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500"
            :class="{ 'border-red-500': form.errors.name }"
            placeholder="Enter department name"
            required
          />
          <div v-if="form.errors.name" class="text-red-500 text-sm mt-1">{{ form.errors.name }}</div>
        </div>

        <!-- Buttons -->
        <div class="flex justify-end gap-2 pt-4">
          <a href="/admin/departments" class="px-4 py-2 rounded-lg border border-gray-300 hover:bg-gray-100 text-gray-700">Cancel</a>
          <button
            type="submit"
            :disabled="loading"
            class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 flex items-center gap-2"
          >
            <i class="fa fa-save"></i>
            <span v-if="!loading">Update Department</span>
            <span v-else>Updating...</span>
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import Layout from '../../../Shared/Layout.vue';
import { useForm } from '@inertiajs/vue3';
import { useSnackbar } from 'vue3-snackbar';

export default {
  layout: Layout,
  props: {
    department: Object,
  },
  data() {
    return {
      loading: false,
      snackbar: useSnackbar(),
      form: useForm({
        name: this.department.name,
      }),
    };
  },
  methods: {
    submit() {
      this.loading = true;
      this.form.put(`/admin/departments/${this.department.id}`, {
        onSuccess: () => {
          this.snackbar.add({
            type: 'success',
            text: 'Department updated successfully!',
          });
        },
        onError: (errors) => {
          const message =
            errors?.error ||
            this.form.errors?.[Object.keys(this.form.errors)[0]] ||
            'Something went wrong.';
          this.snackbar.add({
            type: 'error',
            text: message,
          });
        },
        onFinish: () => {
          this.loading = false;
        },
      });
    },
  },
};
</script>
