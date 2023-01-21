<template>

</template>

<script>
import { ref } from 'vue'
import { Switch } from '@headlessui/vue'

export default {
components: {
    Switch,
},
    data() {
        return {
            isToggled: false,
        };
    },
}
</script>
