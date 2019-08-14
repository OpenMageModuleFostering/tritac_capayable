<?php
class Tritac_CapayableApiClient_Enums_InvoiceCreditStatus extends Tritac_CapayableApiClient_Enums_Enum{
    
    // UNPAID (0) The invoice is fully unpaid. The original invoice will be credited by 
    // the given credit amount (AmountCredited = CreditAmount)
    const UNPAID = 0;

    // ALREADY_PAID (1) The invoice is fully paid. Credits on the original invoice is 
    // are possible. The webshop should do a refund. (AmountCredited = 0)
    const ALREADY_PAID = 1;

    // PARTIALLY_PAID (2) The invoice is partially paid. If the remaining unpaid 
    // amount is larger than the credit amount, it will be credited (AmountCredited
    // = CreditAmount). If not, the unpaid amount is credited, the webshop should 
    // do a refund of the leftover (AmountCredited = unpaid amount, 
    // AmountNotCredited = CreditAmount – unpaid amount)
    const PARTIALLY_PAID = 2;

    // EXCEEDS_PERIOD_LIMIT (3) The call is later than 14 days after the 
    // InvoiceDate. Credit is no longer possible. (AmountCredited = 0)
    const EXCEEDS_PERIOD_LIMIT = 3;

}