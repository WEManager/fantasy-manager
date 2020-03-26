<div>

    <form wire:submit.prevent="save">
        <div class="form-group">
            <label for="clubname">Email address</label>
            <input wire:model="name" type="text" class="form-control" id="clubname"
                   placeholder="{{ __('Clubname') }}"/>

            <div class="form-row" style="margin-top: 15px">
                <div class="col-sm-4">
                    <input wire:model="mainColor" type="color" class="form-control" id="maincolor"/>
                </div>

                <div class="col-sm-4">
                    <input wire:model="secondColor" type="color" class="form-control" id="secondcolor"/>
                </div>

                <div class="col-sm-4">
                    <input wire:model="thirdColor" type="color" class="form-control" id="thirdcolor"/>
                </div>
            </div>


            <button type="submit" class="btn btn-success">Save</button>
        </div>
    </form>

</div>
