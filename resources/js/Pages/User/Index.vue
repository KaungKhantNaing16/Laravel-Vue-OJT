<template>
    <AppLayout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                User Index
            </h2>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <jet-input
                        type="text"
                        class="block m-4 w-60"
                        v-model="form.search"
                        placeholder="Search user..."
                    />
                    <div class="flex flex-col">
                        <div class="my-2 overflow-x-auto sm:mx-6 lg:mx-8">
                            <div class="py-2 align-middle inline-block min-w-full ms:px-6 lg:px-8">
                                <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                                    <table class="min-w-full divided-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider ">Name</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider ">Status</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider ">Role</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider ">Created At</th>
                                                <th scope="col" class="relative px-6 py-3">
                                                    <span class="sr-only">Edit</span>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divided-y divided-gray-200">
                                            <tr v-if="!users.data.length">
                                                <td class="p-4 text-center text-gray-900" colspan="5">
                                                    No data
                                                </td>
                                            </tr>
                                            <tr v-for="user in users.data" :key="user.id">
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="flex items-center">
                                                        <div class="flex-shrink-0 h-10 w-10">
                                                            <img :src="user.profile_photo_url" alt="user-profile" class="rounded-full ring-2 ring-slate">
                                                        </div>
                                                        <div class="ml-4">
                                                            <div class="text-sm font-medium text-gray-900">
                                                                {{ user.name }}
                                                            </div>
                                                            <div class="text-sm text-gray-500">
                                                                {{ user.email }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                        Active
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ user.role }}
                                                </td>
                                                 <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ user.created_at }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                    <Link :href="route('users.show', user.id)" class="text-blue-700 hover:text-white border border-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 text-center mr-2 mb-2 dark:border-blue-500 dark:text-blue-500 dark:hover:text-white dark:hover:bg-blue-600 dark:focus:ring-blue-800" v-if="user.can.view">Show</Link>
                                                    <Link :href="route('users.edit', user.id)" class="text-yellow-400 hover:text-white border border-yellow-400 hover:bg-yellow-500 focus:ring-4 focus:outline-none focus:ring-yellow-300 font-medium rounded-lg text-sm px-4 py-2 text-center mr-2 mb-2 dark:border-yellow-300 dark:text-yellow-300 dark:hover:text-white dark:hover:bg-yellow-400 dark:focus:ring-yellow-900"  v-if="user.can.update">Edit</Link>
                                                    <button @click="deleteUser(user.id)" class="text-red-700 hover:text-white border border-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-4 py-2 text-center mr-2 mb-2 dark:border-red-500 dark:text-red-500 dark:hover:text-white dark:hover:bg-red-600 dark:focus:ring-red-900" type="button"  v-if="user.can.delete">Delete</button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <JetPagination class="m-5" :links="users.links" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
    </AppLayout>
    
</template>

<script>
import AppLayout from '@/Layouts/AppLayout.vue';
import { reactive, watchEffect } from "vue";
import { Link } from '@inertiajs/inertia-vue3';
import { Inertia } from '@inertiajs/inertia';
import { pickBy } from 'lodash';
import JetButton from '@/Jetstream/Button';
import JetInput from '@/Jetstream/Input';
import JetPagination from '@/Components/Pagination';

export default {
    components: {
        AppLayout,
        Link,
        JetButton,
        JetInput,
        JetPagination
    },

    props: {
        users: Object,
        filters: Object,
    },

    setup(props) {
        const form = reactive({
            search: props.filters.search,
        });

        watchEffect(() => {
            const query = pickBy(form);

            Inertia.replace(
                route('users.index', Object.keys(query).length ? query : {})
            );
        });

        const deleteUser = (userId) => {
            const result = confirm("Are you sure want to delete?");
            if (result) {
                    Inertia.delete(route("posts.destroy", userId), {
                    preserveScroll: true,
                });
            }
        };

        return {
            form,
            deleteUser
        }
    }
}
</script>