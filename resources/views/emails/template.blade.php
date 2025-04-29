<!DOCTYPE html>
<html>
<body>
    <div style="max-width: 600px; margin: 0 auto; font-family: Arial, sans-serif;">
        <img src="{{ $tracking_pixel }}" width="1" height="1" style="display:none">
        <p>Dear {{ $name }},</p>
        
        <p>I hope you're doing well. A few months ago, we received your request for a quote, but it seems the order didn't move forward. I wanted to check in and see if you have any new projects or requirements we can assist with.</p>
        
        <p>If you're considering a new order, please share the details—style, size, and quantity—and I'd be happy to arrange a quote for you. Additionally, we can beat the best quote you're getting without compromising on quality. Let us know how we can support your needs, and we'll ensure you get the best value.</p>
        
        <p>Looking forward to your response!</p><a href="{{ $tracked_link }}">Click here for details</a>
        
        <p style="border-top: 1px solid #eee; padding-top: 15px; margin-top: 30px;">
            Best Regards,<br>
            <strong>{{ $senderName }}</strong><br>
            {{ $senderRole }}<br>
            <!-- Transparent logo with no forced background -->
            <img src="https://images.nexonpackaging.com/fulllogo.png"
            alt="Nexon Packaging Logo"
            style="max-width: 220px; height: auto; margin: 15px 0; display: block;">
            <br>
            🔗 <a href="{{ $companyWebsite }}">{{ $companyWebsite }}</a><br>
            📞 (904) 706-8883
            
        </p>

        <div style="font-size: 0.8em; color: #666; margin-top: 30px;">
            <p>
                {{-- <a href="{{ $unsubscribeLink }}">Unsubscribe from future emails</a><br> --}}
                <span style="color: #a60404;">{{ $disclaimer }}</span>
            </p>
        </div>
    </div>
</body>
</html>