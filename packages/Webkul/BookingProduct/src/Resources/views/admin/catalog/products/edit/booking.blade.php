@php
    $bookingProduct = app('\Webkul\BookingProduct\Repositories\BookingProductRepository')->findOneByField('product_id', $product->id)
@endphp

{!! view_render_event('bagisto.admin.catalog.product.edit_form_accordian.booking.before', ['product' => $product]) !!}

<div class="relative p-[16px] bg-white dark:bg-gray-900 rounded-[4px] box-shadow">
    <v-booking-information></v-booking-information>
</div>

{!! view_render_event('bagisto.admin.catalog.product.edit_form_accordian.booking.after', ['product' => $product]) !!}

@pushOnce('scripts')
    <script type="text/x-template" id="v-booking-information-template">
        <x-admin::form
            enctype="multipart/form-data"
            method="PUT"
        >
            <div>
                <!-- Booking Type -->
                <x-admin::form.control-group class="w-full mb-[10px]">
                    <x-admin::form.control-group.label class="required">
                        @lang('booking::app.admin.catalog.products.edit.type.booking.booking_type')
                    </x-admin::form.control-group.label>

                    <x-admin::form.control-group.control
                        class="hidden"
                        name="booking[type]"
                        ::value="booking.type"
                    >
                    </x-admin::form.control-group.control>

                    <x-admin::form.control-group.control
                        type="select"
                        name="booking[type]"
                        rules="required"
                        ::value="booking.type"
                        :label="trans('booking::app.admin.catalog.products.edit.type.booking.booking_type')"
                        v-model="booking.type"
                        ::disabled="! is_new"
                    >
                        @foreach (['default', 'appointment', 'event', 'rental', 'table'] as $item)
                            <option value={{ $item }}>
                                @lang('booking::app.admin.catalog.products.edit.type.booking.type.' . $item)
                            </option>
                        @endforeach
                    </x-admin::form.control-group.control>

                    <x-admin::form.control-group.error 
                        control-name="booking[type]"
                    >
                    </x-admin::form.control-group.error>
                </x-admin::form.control-group>

                <!-- Location -->
                <x-admin::form.control-group class="w-full mb-[10px]">
                    <x-admin::form.control-group.label class="required">
                        @lang('booking::app.admin.catalog.products.edit.type.booking.location')
                    </x-admin::form.control-group.label>

                    <x-admin::form.control-group.control
                        type="text"
                        name="booking[location]"
                        :label="trans('booking::app.admin.catalog.products.edit.type.booking.location')"
                        v-model="booking.location"
                    >
                    </x-admin::form.control-group.control>

                    <x-admin::form.control-group.error 
                        control-name="booking[location]"
                    >
                    </x-admin::form.control-group.error>
                </x-admin::form.control-group>

                <!-- QTY -->
                <x-admin::form.control-group
                   class="w-full mb-[10px]"
                   v-if="booking.type == 'default' || booking.type == 'appointment' || booking.type == 'rental'"
                >
                    <x-admin::form.control-group.label class="required">
                        @lang('booking::app.admin.catalog.products.edit.type.booking.qty')
                    </x-admin::form.control-group.label>

                    <x-admin::form.control-group.control
                        type="text"
                        name="booking[qty]"
                        :label="trans('booking::app.admin.catalog.products.edit.type.booking.qty')"
                        required="required|numeric|min:0"
                        v-model="booking.qty"
                    >
                    </x-admin::form.control-group.control>

                    <x-admin::form.control-group.error 
                        control-name="booking[qty]"
                    >
                    </x-admin::form.control-group.error>
                </x-admin::form.control-group>

                <!-- Available Every Week -->
                <x-admin::form.control-group
                    class="w-full mb-[10px]"
                    v-if="booking.type != 'event' && booking.type != 'default'"
                >
                    <x-admin::form.control-group.label class="required">
                        @lang('booking::app.admin.catalog.products.edit.type.booking.available-every-week.title')
                    </x-admin::form.control-group.label>

                    <x-admin::form.control-group.control
                        type="select"
                        name="booking[available_every_week]"
                        rules="required"
                        :label="trans('booking::app.admin.catalog.products.edit.type.booking.available-every-week.title')"
                        v-model="booking.available_every_week"
                        @change="availableEveryWeekSwatch=false"
                    >
                        <option value="1">
                            @lang('booking::app.admin.catalog.products.edit.type.booking.available-every-week.yes')
                        </option>

                        <option value="0">
                            @lang('booking::app.admin.catalog.products.edit.type.booking.available-every-week.no')
                        </option>
                    </x-admin::form.control-group.control>

                    <x-admin::form.control-group.error 
                        control-name="booking[available_every_week]"
                    >
                    </x-admin::form.control-group.error>
                </x-admin::form.control-group>

                <div v-if="! parseInt(booking.available_every_week)">
                    <!-- Available From  -->
                    <x-admin::form.control-group
                        class="w-full mb-[10px]"
                        v-if="availableEveryWeekSwatch && available_every_week"
                    >
                        <x-admin::form.control-group.label class="required">
                            @lang('booking::app.admin.catalog.products.edit.type.booking.available-from')
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="datetime"
                            name="booking[available_from]"
                            rules="required|date_format:yyyy-MM-dd HH:mm:ss|after:{{\Carbon\Carbon::yesterday()->format('Y-m-d 23:59:59')}}"
                            v-model="booking.available_from"
                            :label="trans('booking::app.admin.catalog.products.edit.type.booking.available-from')"
                            :placeholder="trans('booking::app.admin.catalog.products.edit.type.booking.available-from')"
                            ref="available_from"
                        >
                        </x-admin::form.control-group.control>

                        <x-admin::form.control-group.error 
                            control-name="booking[available_from]"
                        >
                        </x-admin::form.control-group.error>
                    </x-admin::form.control-group>

                    <!-- Available To -->
                    <x-admin::form.control-group
                        class="w-full mb-[10px]"
                        v-if="(
                            availableEveryWeekSwatch 
                            && available_every_week == 0
                        ) && (
                            bookingSwatch 
                            && booking_type != 'event'
                        )"
                    >
                        <x-admin::form.control-group.label class="required">
                            @lang('booking::app.admin.catalog.products.edit.type.booking.available-to')
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="datetime"
                            name="booking[available_to]"
                            rules="required|date_format:yyyy-MM-dd HH:mm:ss|after:available_from"
                            v-model="booking.available_to"
                            :label="trans('booking::app.admin.catalog.products.edit.type.booking.available-to')"
                            :placeholder="trans('booking::app.admin.catalog.products.edit.type.booking.available-to')"
                            ref="available_to"
                        >
                        </x-admin::form.control-group.control>

                        <x-admin::form.control-group.error 
                            control-name="booking[available_to]"
                        >
                        </x-admin::form.control-group.error>
                    </x-admin::form.control-group>
                </div>
            </div>
        </x-admin::form>
        
        <div>
            <div
                class="default-booking-section"
                v-if="booking.type == 'default'"
            >
                @include ('booking::admin.catalog.products.edit.booking.default', ['bookingProduct' => $bookingProduct])
            </div>

            <div
                class="appointment-booking-section"
                v-if="booking.type == 'appointment'"
            >
                @include ('booking::admin.catalog.products.edit.booking.appointment', ['bookingProduct' => $bookingProduct])
            </div>

            <div
                class="event-booking-section"
                v-if="booking.type == 'event'"
            >
                @include ('booking::admin.catalog.products.edit.booking.event', ['bookingProduct' => $bookingProduct])
            </div>

            <div class="rental-booking-section" v-if="booking.type == 'rental'">
                @include ('booking::admin.catalog.products.edit.booking.rental', ['bookingProduct' => $bookingProduct])
            </div>

            <div class="table-booking-section" v-if="booking.type == 'table'">
                @include ('booking::admin.catalog.products.edit.booking.table', ['bookingProduct' => $bookingProduct])
            </div>
        </div>
    </script>

    <script type="module">
        app.component('v-booking-information', {
            template: '#v-booking-information-template',

            data() {
                return {
                    is_new: @json($bookingProduct) ? false : true,

                    booking: @json($bookingProduct) ? @json($bookingProduct) : {

                        type: 'default',

                        location: '',

                        qty: 0,

                        available_every_week: 0,

                        availableEveryWeekSwatch: true,

                        available_from: '',

                        available_to: ''
                    }
                }
            },

            created() {
                this.booking.available_from = "{{ $bookingProduct && $bookingProduct->available_from ? $bookingProduct->available_from->format('Y-m-d H:i:s') : '' }}";

                this.booking.available_to = "{{ $bookingProduct && $bookingProduct->available_to ? $bookingProduct->available_to->format('Y-m-d H:i:s') : '' }}";
            }
        });
    </script>

    {{-- @include ('bookingproduct::admin.catalog.products.accordians.booking.slots') --}}
@endpushOnce
