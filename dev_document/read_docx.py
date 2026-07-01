import zipfile
import xml.etree.ElementTree as ET

def get_docx_text(path):
    namespaces = {'w': 'http://schemas.openxmlformats.org/wordprocessingml/2006/main'}
    text = []
    with zipfile.ZipFile(path) as docx:
        tree = ET.fromstring(docx.read('word/document.xml'))
        for paragraph in tree.iter(f"{{{namespaces['w']}}}p"):
            p_text = []
            for run in paragraph.iter(f"{{{namespaces['w']}}}r"):
                for text_node in run.iter(f"{{{namespaces['w']}}}t"):
                    if text_node.text:
                        p_text.append(text_node.text)
            text.append(''.join(p_text))
    return '\n'.join(text)

if __name__ == '__main__':
    content = get_docx_text('/home/asifk/projects/SettleANZ/dev_document/Service.docx')
    with open('/home/asifk/projects/SettleANZ/dev_document/Service_extracted.txt', 'w', encoding='utf-8') as f:
        f.write(content)
    print("Successfully extracted docx content.")
