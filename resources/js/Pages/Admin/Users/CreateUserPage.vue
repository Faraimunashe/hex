<template>
  <Head title="Create User" />
  <vue3-snackbar top right :duration="4000"></vue3-snackbar>
  <div class="p-6 min-h-screen bg-gray-100">
    <!-- Breadcrumb -->
    <nav class="text-sm text-gray-600 mb-6 flex items-center gap-2">
      <i class="fa fa-home text-gray-400"></i>
      <span>/</span>
      <a href="/users" class="hover:text-blue-600">Users</a>
      <span>/</span>
      <span class="font-semibold text-blue-700">Create</span>
    </nav>

    <!-- Card -->
    <div class="bg-white rounded-xl shadow-lg p-8 max-w-3xl mx-auto border border-blue-100">
      <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center gap-2">
        <i class="fa fa-user-plus text-blue-600"></i> Create New User
      </h2>

      <form @submit.prevent="submit" class="space-y-5">
        <!-- Name -->
        <div>
          <label class="block text-gray-700 font-medium mb-1">Full Name</label>
          <input
            v-model="form.name"
            type="text"
            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500"
            :class="{ 'border-red-500': form.errors.name }"
            required
          />
          <div v-if="form.errors.name" class="text-red-500 text-sm mt-1">{{ form.errors.name }}</div>
        </div>

        <!-- Email -->
        <div>
          <label class="block text-gray-700 font-medium mb-1">Email Address</label>
          <input
            v-model="form.email"
            type="email"
            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500"
            :class="{ 'border-red-500': form.errors.email }"
            required
          />
          <div v-if="form.errors.email" class="text-red-500 text-sm mt-1">{{ form.errors.email }}</div>
        </div>

        <!-- Role -->
        <div>
          <label class="block text-gray-700 font-medium mb-1">Role</label>
          <select
            v-model="form.role"
            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500"
            :class="{ 'border-red-500': form.errors.role }"
            required
          >
            <option value="" disabled>Select a role</option>
            <option v-for="role in roles" :key="role.id" :value="role.name">{{ role.display_name }}</option>
          </select>
          <div v-if="form.errors.role" class="text-red-500 text-sm mt-1">{{ form.errors.role }}</div>
        </div>

        <!-- Password -->
        <div>
          <label class="block text-gray-700 font-medium mb-1">Password</label>
          <input
            v-model="form.password"
            type="password"
            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500"
            :class="{ 'border-red-500': form.errors.password }"
            required
          />
          <div v-if="form.errors.password" class="text-red-500 text-sm mt-1">{{ form.errors.password }}</div>
        </div>

        <div>
          <label class="block text-gray-700 font-medium mb-1">Confirm Password</label>
          <input
            v-model="form.password_confirmation"
            type="password"
            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500"
            :class="{ 'border-red-500': form.errors.password_confirmation }"
            required
          />
          <div v-if="form.errors.password" class="text-red-500 text-sm mt-1">{{ form.errors.password_confirmation }}</div>
        </div>

        <!-- Buttons -->
        <div class="flex justify-end gap-2 pt-4">
          <a href="/admin/users" class="px-4 py-2 rounded-lg border border-gray-300 hover:bg-gray-100 text-gray-700">Cancel</a>
          <button
            type="submit"
            :disabled="loading"
            class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 flex items-center gap-2"
          >
            <i class="fa fa-save"></i>
            <span v-if="!loading">Create User</span>
            <span v-else>Creating...</span>
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import Layout from '../../../Shared/Layout.vue'
import { useForm } from '@inertiajs/vue3'
import { useSnackbar } from 'vue3-snackbar'

export default {
  layout: Layout,
  props: {
    roles: Array
  },
  data() {
    return {
      loading: false,
      snackbar: useSnackbar(),
      form: useForm({
        name: '',
        email: '',
        role: '',
        password: '',
        password_confirmation: ''
      }),
    }
  },
  methods: {
    submit() {
      this.loading = true
      this.form.post('/admin/users', {
        onSuccess: () => {
          this.snackbar.add({
            type: 'success',
            text: 'User created successfully!',
          })
          this.form.reset()
        },
        onError: (errors) => {
          const message =
            errors?.error ||
            this.form.errors?.[Object.keys(this.form.errors)[0]] ||
            'Something went wrong.'
          this.snackbar.add({
            type: 'error',
            text: message,
          })
        },
        onFinish: () => {
          this.loading = false
        },
      })
    },
  },
}
</script>


