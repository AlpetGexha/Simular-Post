<div x-data="{
    isFollow: $wire.isFollow,
    toogleLike: function() {
        if (this.isFollow) {
            this.isFollow = false;
        } else {
            this.isFollow = true;
        }
    }
}">

    <x-primary-button
        wire:click='follow()'
        wire:loading.attr="disabled"
        wire:loading.class="!cursor-wait"
        x-on:click="toogleLike"
        class="ml-3">
        <span>
            {{-- add text base on isFollow --}}
            <span x-show="isFollow">Unfollow</span>
            <span x-show="!isFollow">Follow</span>
        </span>
    </x-primary-button>
</div>
