@inject ('bookingSlotHelper', 'Webkul\BookingProduct\Helpers\EventTicket')

<span class="value">
    {!! $bookingSlotHelper->getEventDate($bookingProduct) !!}
</span>

<!-- Event Vue Component -->
<v-event-tickets></v-event-tickets>

@pushOnce('scripts')
    <script type="text/x-template" id="v-event-tickets-template">
        <div class="book-slots">
            <label>
                @lang('booking::app.shop.products.book-your-ticket')
            </label>

            <div v-for="(ticket, index) in tickets">
                <div class="ticket-info">
                    <div v-text="ticket.name"></div>

                    <div v-if="ticket.original_formatted_price">
                        <span
                            class="mr-1.5 line-through"
                            v-text="ticket.original_formatted_price"
                        >
                        </span>

                        <span
                            class="text-lg"
                            v-text="ticket.formatted_price_text"
                        >
                        </span>
                    </div>

                    <div
                        v-else
                        v-text="ticket.formatted_price_text"
                    >
                    </div>
                </div>

                <x-shop::quantity-changer
                    ::name="'booking[qty][' + ticket.id + ']'"
                    rules="required|numeric|min_value:0"
                    ::value="defaultQty"
                    ::min-quantity="defaultQty"
                    class="gap-x-2.5 w-28 max-h-9 py-1.5 px-3.5 rounded-[10px]"
                >
                </x-shop::quantity-changer>
                
                <div v-text="ticket.description"></div>
            </div>
        </div>
    </script>

    <script type="module">
        app.component('v-event-tickets', {
            template: '#v-event-tickets-template',

            data() {
                return {
                    tickets: @json($bookingSlotHelper->getTickets($bookingProduct)),
                }
            },

            computed: {
                defaultQty() {
                    return this.tickets.length > 1 ? 0 : 1;
                }
            }
        });
    </script>
@endpushOnce