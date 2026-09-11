from pathlib import Path

root = Path('/home/ubuntu/work-bamusi/app/Views/admin')
needle = '<form '
for path in root.rglob('*.php'):
    text = path.read_text()
    if '<form' not in text or 'method="post"' not in text or 'csrf_field()' in text:
        continue
    lines = text.splitlines(keepends=True)
    out = []
    in_post_form = False
    injected = False
    for line in lines:
        out.append(line)
        if '<form' in line and 'method="post"' in line:
            in_post_form = True
            injected = False
        elif in_post_form and not injected and '>' in line:
            indent = line[:len(line) - len(line.lstrip())] + '    '
            newline = '\n' if line.endswith('\n') else ''
            out.append(f'{indent}<?= csrf_field() ?>{newline}')
            injected = True
            in_post_form = False
    path.write_text(''.join(out))
    print(path)
