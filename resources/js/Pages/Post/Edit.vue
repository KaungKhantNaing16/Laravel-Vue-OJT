<template>
    <AppLayout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                User Edit
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <JetFormSection @submitted="updateProfileInformation">
                    <template #title>
                        Profile Information
                    </template>

                    <template #description>
                        Update your account's profile information and email address.
                    </template>

                    <template #form>
                        <!-- Name -->
                        <div class="col-span-6 sm:col-span-4">
                            <JetLabel for="name" value="Name" />
                            <JetInput
                                id="name"
                                v-model="form.name"
                                type="text"
                                class="mt-1 block w-full"
                                autocomplete="name"
                            />
                            <JetInputError :message="form.errors.name" class="mt-2" />
                        </div>

                        <!-- Email -->
                        <div class="col-span-6 sm:col-span-4">
                            <JetLabel for="email" value="Email" />
                            <JetInput
                                id="email"
                                v-model="form.email"
                                type="email"
                                class="mt-1 block w-full"
                            />
                            <JetInputError :message="form.errors.email" class="mt-2" />

                            <div v-if="$page.props.jetstream.hasEmailVerification && user.email_verified_at === null">
                                <p class="text-sm mt-2">
                                    Your email address is unverified.

                                    <Link
                                        :href="route('verification.send')"
                                        method="post"
                                        as="button"
                                        class="underline text-gray-600 hover:text-gray-900"
                                        @click.prevent="sendEmailVerification"
                                    >
                                        Click here to re-send the verification email.
                                    </Link>
                                </p>

                                <div v-show="verificationLinkSent" class="mt-2 font-medium text-sm text-green-600">
                                    A new verification link has been sent to your email address.
                                </div>
                            </div>
                        </div>

                        <!-- Role -->
                        <div
                            class="col-span-6 sm:col-span-4"
                            v-if="$page.props.permission.users.editRole">
                            <jet-label for="role" value="Role" />
                            <jet-select
                                id="role"
                                class="mt-1 block w-full"
                                v-model="form.role"
                                :options="roles"
                            />
                            <jet-input-error :message="form.errors.role" class="mt-2" />
                        </div>
                    </template>

                    <template #actions>
                        <JetActionMessage :on="form.recentlySuccessful" class="mr-3">
                            Saved.
                        </JetActionMessage>

                        <JetButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                            Save
                        </JetButton>
                    </template>
                </JetFormSection>
            </div>
        </div>
    </AppLayout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout.vue';
import { ref } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import { Link, useForm } from '@inertiajs/inertia-vue3';
import JetButton from '@/Jetstream/Button.vue';
import JetFormSection from '@/Jetstream/FormSection.vue';
import JetInput from '@/Jetstream/Input.vue';
import JetInputError from '@/Jetstream/InputError.vue';
import JetLabel from '@/Jetstream/Label.vue';
import JetActionMessage from '@/Jetstream/ActionMessage.vue';
import JetSecondaryButton from '@/Jetstream/SecondaryButton.vue';
import JetSelect from "@/Components/Select";

export default {
    components: {
        AppLayout,
        JetButton,
        Link,
        useForm,
        JetFormSection,
        JetInput,
        JetInputError,
        JetLabel,
        JetActionMessage,
        JetSecondaryButton,
        JetSelect
    },

    props: {
        posts: Object,
    },

    setup(props) {
        const form = useForm({
            _method: 'PUT',
            title: props.post.title,
            content: props.post.content,
        });

        const editPost = () => {
            form.post(route('posts.update', props.post.id), {
                preserveScroll: true
            });
        };

        return {
            form,
            editPost
        }
    }
}
</script>