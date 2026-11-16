# NTMP landing page

The current implementation, build instructions, verification evidence and deployment requirements are documented in [RELEASE.md](RELEASE.md).

Rebuild: `python tools/ntmp/match_reference.py`

Backend checks: `python tools/ntmp/test_backend.py`

Browser checks: start `php -S 127.0.0.1:8126 -t .`, then run `python tools/ntmp/verify.py`.

Release ZIP: `python tools/ntmp/package_release.py`

Source has been pushed to GitHub; the Pages workflow publishes the static site. PHP-host deployment and real CRM delivery remain unverified. See RELEASE.md for the distinction and required host configuration.
