# Email Addresses to Update

This document lists all placeholder email addresses in the codebase that need to be replaced with the actual domain-based email addresses once Jeremiah provides the correct domain.

## Current Placeholder: jeremiahbbrown1997@gmail.com

### Files that need updating:

1. **resources/views/welcome.blade.php** (lines 480-481)
   - Contact email link in footer/contact section

2. **resources/views/properties/show.blade.php** (lines 259, 307)
   - Property inquiry email link
   - Agent contact information

3. **resources/views/properties/index.blade.php** (line 239)
   - Property inquiry email link

4. **resources/views/legal/terms-of-service.blade.php** (line 244)
   - Contact email in legal document

5. **resources/views/legal/privacy-policy.blade.php** (line 194)
   - Contact email in legal document

6. **resources/views/legal/cookie-policy.blade.php** (line 228)
   - Contact email in legal document

7. **resources/views/emails/property-packet.blade.php** (lines 187, 197)
   - Agent email in email template

8. **resources/views/components/property-booking.blade.php** (line 77)
   - Property tour request email

9. **resources/views/components/footer.blade.php** (line 116)
   - Footer contact email

10. **resources/views/agents.blade.php** (lines 146, 193, 250)
    - Agent contact information and email links

11. **app/Services/PropertyPacketService.php** (lines 70, 151)
    - Email service configuration for property packets

12. **resources/views/components/owner-financing-form.blade.php** (line in script)
    - Owner financing application email target

## Configuration Files:

13. **config/mail.php** (line 112)
    - Default "from" email address (hello@example.com)

## Test/Development Files:

14. **app/Console/Commands/TestPropertyPacket.php** (line 40)
    - Test email address

15. **database/seeders/DatabaseSeeder.php** (line 20)
    - Test user email

16. Various test files with test@example.com

## Replacement Instructions:

When the correct domain is provided, replace `jeremiahbbrown1997@gmail.com` with the appropriate domain-based email addresses such as:
- `jeremiah@[domain].com` for personal contact
- `info@[domain].com` for general inquiries
- `contact@[domain].com` for contact forms

Also update the mail configuration in `config/mail.php` to use the proper domain email.

## Search Commands for Quick Updates:

```bash
# Find all instances of the Gmail address
grep -r "jeremiahbbrown1997@gmail.com" resources/ app/

# Find example.com addresses
grep -r "@example.com" resources/ app/ config/
```
